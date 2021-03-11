<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header-with-button">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="m-0">categories</h5>
                </div>
                <div class="main-layout-card-header-contents text-right">
                    <button class="btn btn-primary app-color mobile-btn" data-toggle="modal"
                            data-target="#add-edit-modal"
                            @click.prevent="addEditAction('')">
                        {{ $t('lang.add') }}
                    </button>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"></pre-loader>
        <datatable-component v-else class="main-layout-card-content" :options="tableOptions"></datatable-component>

        <!-- Modal -->
        <!--Pass current modelId and isActive for rerender modal on DOM -->
        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <createcategories-form
                        class="modal-content"
                        :modalID="modalID"
                        v-if="isActive"
                        :id="selectedItemId"
                ></createcategories-form>
            </div>
        </div>

        <!-- Delete Modal -->
        <confirmation-modal
                id="confirm-delete"
                :message="'user_will_be_deleted_permanently'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @confirmationModalButtonAction="confirmationModalButtonAction"
        ></confirmation-modal>


        <disable_enable_user_action
                id="confirm-disable"
                :message="'this_user_will_be_disabled'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @disableEnableUserAction="disableEnableUserAction"
        ></disable_enable_user_action>

        <disable_enable_user_action
                id="confirm-enable"
                :message="'this_user_will_be_enabled'"
                :firstButtonName="'yes'"
                :secondButtonName="'no'"
                @disableEnableUserAction="disableEnableUserAction"
        ></disable_enable_user_action>
        <!-- Modal End-->
    </div>
</template>

<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
              let instance = this;
            return {
                hidePreLoader: true,
                tabName: 'category',
                name: '',
                modalID: '#add-edit-modal',
                isActive: false,
                selectedItemId: '',
                tableOptions: {
                    tableName: 'category',
                    columns: [
                         {
                            title: 'lang.name',
                            key: 'name',
                            type: 'text',
                            uniquefield: 'id',
                            sortable: true
                        },
                        {title: 'arabic name', key: 'nameAR', type: 'text', sortable: true},
                        {title: 'icon', type: 'text', key: 'icon', sortable: false},
                        {
                            title: 'lang.status', key: 'activation', type: 'custom',
                            modifier: function (rowData) {
                                console.log(rowData);
                                if (rowData == '1') {
                                    return {
                                        value: instance.$t('lang.active'),
                                        class: "booking-status bg-success"
                                    }
                                } else if (rowData == '0') {
                                    return {
                                        value: instance.$t('lang.inactive'),
                                        class: "booking-status bg-warning"
                                    }
                                }
                            }
                        },
                        {
                            title: 'lang.action', key: 'action', type: 'component',   componentName: 'vendor-action-component',
                            modifier: function (value) {
                                if (value) return false;
                                else return true;
                            }
                        }
                    ],
                    source: '/categorylist',
                    right_align: ['action'],
                },
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
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
                instance.deleteDataMethod('/user-delete/' + instance.deleteID, instance.deleteIndex);
            },
            disableEnableUserAction() {

                let instance = this;
                instance.setPreLoader(false);
                $('#confirm-disable').modal('hide');
                $('#confirm-enable').modal('hide');

                axios.post(`/disableEnableUser/${instance.deleteID}`)
                    .then(response => {
                        instance.setPreLoader(true);
                        instance.$hub.$emit('reloadDataTable');
                        instance.showSuccessAlert(response.data.message);
                    })
                    .catch(error => {
                        instance.setPreLoader(true);
                        this.showErrorAlert(error.data.message);
                    });
            },
        }
    }
</script>

