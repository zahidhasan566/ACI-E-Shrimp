<template>
    <div>
        <div class="modal fade" id="add-edit-dept" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title modal-title-font" id="exampleModalLabel">{{ title }}</div>
                    </div>
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form class="form-horizontal" id="form" @submit.prevent="handleSubmit(onSubmit)">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <ValidationProvider name="EventName" mode="eager" rules="required"
                                                            v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="EventName">Event Name <span class="error">*</span></label>
                                                <input type="text" class="form-control"
                                                       :class="{'error-border': errors[0]}" id="EventName"
                                                       v-model="EventName" name="EventName" placeholder="Event  Name">
                                                <span class="error-message"> {{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12 col-md-6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="EventStartDate" mode="eager"
                                                            v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="EventStartDate">Event Start Date Time</label>

                                                <datetime format="YYYY-MM-DD H:i:s" width="300px"
                                                          v-model="EventStartDate"></datetime>
                                                <span class="error-message"> {{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="EventEndDate" mode="eager"
                                                            v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="EventEndDate">Event End Date Time</label>

                                                <datetime format="YYYY-MM-DD H:i:s" width="300px"
                                                          v-model="EventEndDate"></datetime>
                                                <span class="error-message"> {{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="EventImage"  style="display: block">Event File    <span class="error">*</span></label>
                                            <input type="file" id="EventImage" @change="imgUpload($event)">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Status" mode="eager" rules="required"
                                                            v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="status">Status <span class="error">*</span></label>
                                                <select class="form-control" v-model="Status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <span class="error-message"> {{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <submit-form v-if="buttonShow" :name="buttonText"/>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        @click="closeModal">Close
                                </button>
                            </div>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {bus} from "../../../app";
import {Common} from "../../../mixins/common";
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import {mapGetters} from "vuex";
import datetime from 'vuejs-datetimepicker';


export default {
    components: {DatePicker, datetime},
    mixins: [Common],
    data() {
        return {
            title: '',
            ShrimpAdvisoryId: '',
            EventName: '',
            EventPeriod: [],
            EventStartDate: '',
            EventEndDate: '',
            Attachment: '',
            Status: '',
            buttonText: '',
            type: 'add',
            AttachmentFlag: 0,
            actionType: '',
            buttonShow: false,
        }
    },
    computed: {},
    mounted() {
        $('#add-edit-dept').on('hidden.bs.modal', () => {
            this.$emit('changeStatus')
        });
        bus.$on('add-edit-eventData', (row) => {
            if (row) {
                let instance = this;
                instance.axiosGet('admin/setting/eventList/get-event-list-info/' + row.ShrimpAdvisoryId, function (response) {
                    var user = response.data;
                    console.log(user);
                    instance.title = 'Update Event';
                    instance.buttonText = "Update";
                    instance.EventID = user.ShrimpAdvisoryId;
                    instance.EventName = user.AttachmentName;
                    instance.EventStartDate = user.DateFrom;
                    instance.EventEndDate = user.DateTo;
                    instance.Attachment = user.Attachment;
                    instance.Status = user.Status;
                    instance.buttonShow = true;
                    instance.actionType = 'edit';
                }, function (error) {
                    console.log(error)
                });
            } else {
                this.title = 'Add Event';
                this.buttonText = "Add";
                this.EventID = '';
                this.EventName = '';
                this.EventStartDate = '';
                this.EventEndDate = '';
                this.Attachment = '';
                this.Status = '';
                this.buttonShow = true;
                this.actionType = 'add'

            }
            $("#add-edit-dept").modal("toggle");
            // $(".error-message").html("");
        })
    },
    destroyed() {
        bus.$off('add-edit-eventData')
    },
    methods: {
        imgUpload(e) {
            var input = e.target
            var file = input.files[0]
            if (file.size > 5000000) {
                this.errorNoti('Maximum file size 5 MB for Event')
            } else {
                // this.Attachment= file;
                // console.log(this.Attachment)
               this.processImage(file)
            }
        },
        processImage(file) {
            let instance = this
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                instance.Attachment = reader.result
                instance.AttachmentFlag =1
            };
            reader.onerror = function (error) {
                console.log('Error: ', error);
            };
        },
        closeModal() {
            $("#add-edit-dept").modal("toggle");
        },
        checkFieldValue() {
            this.errors = [];
            if (this.Attachment === '') {
                console.log('Attachment' + this.Attachment)
                this.errors.push('error')
                this.errorNoti('No Image For This Event')
            }
        },
        onSubmit() {
            this.checkFieldValue()
            if (this.errors.length === 0) {
                this.$store.commit('submitButtonLoadingStatus', true);
                let url = '';
                if (this.actionType === 'add') url = 'admin/setting/eventList/add-event-list-data';
                else url = 'admin/update/setting/eventList/add-event-list-data'

                console.log('Attachment' + this.Attachment)
                this.axiosPost(url, {
                    EventID: this.EventID,
                    EventName: this.EventName,
                    EventStartFrom: this.EventStartDate,
                    EventEndTo: this.EventEndDate,
                    Attachment: this.Attachment,
                    Status: this.Status,
                    AttachmentFlag: this.AttachmentFlag,
                }, (response) => {
                    this.successNoti(response.message);
                    $("#add-edit-dept").modal("toggle");
                    bus.$emit('refresh-datatable');
                    this.$store.commit('submitButtonLoadingStatus', false);
                }, (error) => {
                    this.errorNoti(error);
                    this.$store.commit('submitButtonLoadingStatus', false);
                })
            }
        }
    }
}
</script>
<style lang="scss">

.mx-datepicker {
    width: 100% !important;
}

.datepicker .picker .picker-content {
    width: 350px !important;
}
</style>

