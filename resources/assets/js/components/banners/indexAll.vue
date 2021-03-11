<template>
    <div class="main-layout-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <span>{{ $t('lang.vendors') }}</span>
                </li>
            </ol>
        </nav>
        <div class="main-layout-card">
            <div class="main-layout-card-header-with-button">
                <div class="main-layout-card-content-wrapper">
                    <div class="main-layout-card-header-contents">
                        <h5 class="bluish-text m-0">{{ $t('lang.vendors') }}</h5>
                    </div>
                    <div class="main-layout-card-header-contents text-right  ">
                        <button class="btn btn-primary app-color" data-toggle="modal" data-target="#add-edit-modal"
                                @click.prevent="addEditAction('')">{{ $t('lang.add') }}
                        </button>
                    </div>
                </div>
            </div>
            <datatable-component class="main-layout-card-content"
                                 :options="tableOptions"/>
        </div>
        <div class="modal fade" id="add-edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered big-modal-dialog" role="document">
                <createbanners-form class="modal-content"
                                    v-if="isActive"
                                    :id="selectedItemId"
                                    :modalID="modalID"/>
            </div>
        </div>
  
        <confirmation-modal id="confirm-delete"
                            :message="'service_deleted_permanently'"
                            :firstButtonName="'yes'"
                            :secondButtonName="'no'"
                            @confirmationModalButtonAction="confirmationModalButtonAction"/>

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
                modalID: '#add-edit-modal',
                isActive: false,
                isActive2: false,
                selectedItemId: '',
                hasData: value => {
                    return !_.isEmpty(value) ? true : false
                },
                tableOptions: {
                    tableName: 'banners',
                    columns: [
                        {title: 'lang.title', key: 'title', type: 'text', uniquefield: 'id', sortable: true},
                        {title: 'lang.image', key: 'image', type: 'text', sortable: true},
                        {
                            title: 'lang.status', key: 'activation', type: 'custom',
                            modifier: function (rowData) {
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
                            title: 'lang.action',
                            key: 'action',
                            type: 'component',
                            componentName: 'Edit-Delete-action-component'
                        }

                    ],
                    right_align: ['action'],
                    source: '/bannerslist',
                    search: false,
                    positionAfter: 'activation',
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
                let instance = this;
                instance.deleteDataMethod('/deletebanner/' + instance.deleteID, instance.deleteIndex);
            },
        }

    }
</script>