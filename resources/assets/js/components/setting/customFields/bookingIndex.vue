<template>
    <div>
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="m-0">{{ $t('lang.booking') }}</h5>
                </div>
                <div class="main-layout-card-header-contents text-right">
                    <button type="button"
                            class="btn btn-primary app-color"
                            data-toggle="modal"
                            data-target="#add-edit-modal"
                            @click.prevent="addEditAction('')">
                        {{ $t('lang.add') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="main-layout-card-content">
            <datatable-component :options="tableOptions"></datatable-component>
        </div>

        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <custom_input_filed_booking v-if="isActive" :id="selectedItemId"
                                                :modalID="modalID"></custom_input_filed_booking>
                </div>
            </div>
        </div>

        <confirmation-modal
                id="confirm-delete"
                :message="'delete_booking_custom_filed'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @confirmationModalButtonAction="confirmationModalButtonAction">
        </confirmation-modal>
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                modalID: '#add-edit-modal',
                isActive: false,
                selectedItemId: '',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'servicecustomfield',
                    columns: [
                        {title: 'lang.field_type', key: 'field_type', type: 'text', sortable: true,},
                        {title: 'lang.label_name', key: 'label', type: 'text', sortable: true,},
                        {title: 'lang.status', key: 'is_enable', type: 'text', sortable: true,},
                        {title: 'lang.show_in_table', key: 'show_in_table', type: 'text', sortable: false,},
                        {
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'action_component'
                        }
                    ],
                    right_align: ['action'],
                    source: '/booking-custom-field',
                    search: true,
                },

            }
        },
        mounted() {
            let instance = this;
            instance.$hub.$on('viewEdit', function (id) {
                instance.addEditAction(id);
            });
            instance.modalCloseAction(instance.modalID);
        },
        methods: {
            confirmationModalButtonAction() {
                // set delete route with global deleteID && deleteIndex.
                let instance = this;
                instance.deleteDataMethod('/customfiledbooking-delete/' + instance.deleteID, instance.deleteIndex);
            },

        }
    }
</script>



