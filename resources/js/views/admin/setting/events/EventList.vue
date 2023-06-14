<template>
    <div class="container-fluid">
        <breadcrumb :options="['Advisory List']">
            <button class="btn btn-primary"  @click="addListDataModal()">Add Event </button>
        </breadcrumb>
        <div class="row" style="padding:8px 0px;">
        </div>
        <advanced-datatable :options="tableOptions">
            <template slot="action" slot-scope="row">
                <a href="javascript:" @click="addListDataModal(row.item)"> <i class="ti-pencil-alt">Edit</i></a>
            </template>
            <template slot="Attachment" slot-scope="row">
                <div>
                    <a target="_blank" :href="`${row.item.AttachmentPath}`">{{row.item.Attachment}}</a>
<!--                    <img  style=" width: 100px" :src="`${image}/uploads/${row.item.EventImage}`" alt="eventImage">-->
                </div>
            </template>
            <template slot="Status" slot-scope="row">
                <span v-if="row.item.Status==='1'">Active</span>
                <span v-else>Inactive</span>
            </template>

        </advanced-datatable>
        <add-edit-eventData @changeStatus="changeStatus" v-if="loading"/>
<!--        <reset-password @changeStatus="changeStatus" v-if="loading"/>-->
    </div>
</template>
<script>
import {Common} from "../../../../mixins/common";
import {bus} from "../../../../app";
import moment from "moment";
import {baseurl} from "../../../../base_url";

export default {
    mixins: [Common],

    data() {
        return {
            image:'',
            tableOptions: {
                source: 'admin/setting/advisoryList',
                search: true,
                slots: [2,5,7],
                hideColumn: ['AttachmentPath','CreatedAt'],
                slotsName: ['Attachment','Status','action'],
                sortable: [2],
                pages: [20, 50, 100],
                addHeader: ['Action'],
                filters: [
                    {
                        type: 'rangepicker',
                    }
                ]
            },
            loading: false,
            cpLoading: false,
        }
    },
    created() {
        this.image= baseurl;
    },
    methods: {
        changeStatus() {
            this.loading = false
        },
        addListDataModal(row = '') {
            this.loading = true;
            setTimeout(() => {
                bus.$emit('add-edit-eventData', row);
            })
        },
        changePassword(row) {
            this.loading = true;
            setTimeout(() => {
                bus.$emit('edit-password', row);
            })
        },
        deleteDept(id) {
            this.deleteAlert(() => {
                this.axiosDelete('users', id, (response) => {
                    this.successNoti(response.message);
                    this.$store.commit('departmentDelete', id);
                    bus.$emit('refresh-datatable');
                }, (error) => {
                    this.errorNoti(error);
                })
            });
        },
        exportData() {
            bus.$emit('export-data','user-list-'+moment().format('YYYY-MM-DD'))
        }
    }
}
</script>

<style>
.datepicker .picker .picker-content {
    width: 350px !important;
}
</style>
