<template>
    <div class="dropdown show action-dropdown">
        <a class="dropdown-toggle"
           href="#"
           id="dropdownMenuLink"
           data-toggle="dropdown">
            <i class="la la-ellipsis-v la-1x"></i>
        </a>
        <div class="dropdown-menu">
            <a v-if="rowData.status === 'pending'"
               href=""
               class="dropdown-item"
               @click.prevent="confirm(rowData.id)">
                {{ $t('lang.confirm') }}
            </a>
            <a v-if="rowData.status === 'pending'"
               href=""
               class="dropdown-item"
               data-toggle="modal"
               data-target="#confirm-cancel-order"
               @click.prevent="cancelBookingFromDatatable(rowData.id)">
                {{ $t('lang.cancel') }}
            </a>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['rowData', 'rowIndex'],
        data() {
            return {}
        },
        mounted() {
    
        },
        methods: {
            readData() {
                this.$emit('readData');
            },
            confirm(id) {
                this.$hub.$emit('confirmBookingFromDatatable', id);
            },
       
            cancelBookingFromDatatable(id) {
                this.$hub.$emit('cancelBookingFromDatatable', id)
            },
         
         
       
        }
    }
</script>
