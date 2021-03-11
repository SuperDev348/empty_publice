<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ $t('lang.orders') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text m-0">{{ $t('lang.orders') }}</h5>
                    </div>
                    <div class="main-layout-card-header-contents text-right  ">
                    </div>
                </div>
            </div>
            <datatable-component class="main-layout-card-content"
                                 :options="tableOptions"/>
        </div>
          <!--Cancel Booking Modal-->
        <confirmation-modal id="confirm-cancel-order"
                            :message="'booking_will_be_canceled'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalButtonAction">
        </confirmation-modal>

    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            let instance = this;
            return {
                hidePreLoader: true,
                isActive: false,
                isActive2: false,
                selectedItemId: '',
                 cancelBookingId: '',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'services',
                    columns: [
                        {title: 'lang.productname', key: 'product_name', type: 'text', uniquefield: 'id', sortable: true},
                        {title: 'lang.full_name', key: 'full_name', type: 'text', sortable: true},
                        {title: 'lang.phone_number', key: 'phone_number', type: 'text', sortable: true},
                        {title: 'lang.quantity', key: 'quantity', type: 'text', sortable: true},
                        {title: 'lang.price_per_product', key: 'product_price', type: 'text', sortable: true},
                        {title: 'lang.total', key: 'total', type: 'text', sortable: true},
                        {
                            title: 'lang.status', key: 'status', type: 'custom',
                            modifier: function (rowData) {

                                if (rowData == 'confirmed') return {
                                    value: instance.$t('lang.confirmed_'),
                                    class: "booking-status bg-success"
                                };
                                else if (rowData == 'pending') return {
                                    value: instance.$t('lang.pending'),
                                    class: "booking-status bg-warning"
                                };
                                else if (rowData == 'canceled') return {
                                    value: instance.$t('lang.canceled'),
                                    class: "booking-status bg-disable"
                                };
                            }
                        },
                             {
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'orders-action-component'
                        }

                    ],
                    right_align: ['action'],
                    source: '/ProductsOrderlist',
                    search: false,
                    customField: true, // For custom field display in datatable.
                    positionAfter: 'activation',
                },

            }
        },
        created() {
             let instance = this;
   
        },
        mounted() {
            let instance = this;
          instance.$hub.$on('confirmBookingFromDatatable', function (id) {
                instance.confirmBooking(id)
            });

            instance.$hub.$on('cancelBookingFromDatatable', function (id) {
                instance.cancelBookingId = id;
            });


        },
        methods: {
            confirmationModalButtonAction() {
                this.cancelBooking(this.cancelBookingId)
            },
               confirmBooking(id) {
                let instance = this;
                this.deleteActionPreLoader(false);
                instance.axiosPost('/actionOrder/' + id, {
                        status: 'confirmed'
                    },
                    function (response) {
                        instance.deleteActionPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                    },
                    function (error) {
                        instance.deleteActionPreLoader(true);
                        instance.errors = error.data.errors;
                    }
                );
            },
            cancelBooking(id) {
                let instance = this;
                $('#confirm-cancel-order').modal('hide');
                this.deleteActionPreLoader(false);
                instance.axiosPost('/actionOrder/' + id, {
                        status: 'canceled'
                    },
                    function (response) {
                        instance.deleteActionPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                    },
                    function (error) {
                        instance.deleteActionPreLoader(true);
                        instance.errors = error.data.errors;
                    });
            },
        }

    }
</script>