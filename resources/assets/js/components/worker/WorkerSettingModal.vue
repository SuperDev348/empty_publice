<template>
    <div>
        <div class="modal-layout-header">
            <div class="row">
                <div class="col-10">
                    <h4 class="m-0">{{ $t('lang.setting_service') }}</h4>
                </div>
                <div class="col-2 text-right">
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                            @click.prevent>
                            <span aria-hidden="true">
                                <i class="la la-close"/>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div class="modal-layout-content" v-else>
                <form>
                    <div class="row">
                        <div v-for="one_s in all_services" v-bind:key="one_s['service'].id"  class="form-group col-md-3">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        v-bind:value="one_s['service'].id"
                                        v-model="ids"                                         
                                        v-bind:checked="one_s['checked']">
                                        {{one_s['service'].title}}
                                </label>
                            </div>                       
                        </div>
                    </div>
                <div class="form-row">
                    <div class="col-12">
                        <button class="btn app-color mobile-btn" type="submit" @click.prevent="save()">
                            {{$t('lang.save') }}
                        </button>
                        <button class="btn cancel-btn mobile-btn"
                                data-dismiss="modal"
                                @click.prevent>
                            {{ $t('lang.cancel') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axiosGetPost from "../../helper/axiosGetPostCommon";

    export default {
        name: "CustomSettingModal",
        extends: axiosGetPost,
        props: ["id", "modalID"],
        data() {
            return {
                all_services: null,                
                isChecked:[],
                ids: [],
                submitted: false
            };
        },
        created() {
            if (this.id) {
                this.getAllServices('/get_all_services/'+this.id);
            }
        },
        methods: {        
            save() {
                let instance = this;
                this.submitted = true;
               
                instance.inputFields = {
                    ids: instance.ids
                };

                if (instance.id) {
                    instance.postDataMethod(
                        `/workerServiceSetting/${instance.id}`,
                        instance.inputFields
                    );
                    instance.submitted = false;
                }
            },
            getAllServices(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(
                    route,
                    function (response) {
                        instance.setPreLoader(true);
                        instance.all_services = response.data.services;      
                        instance.ids = response.data.isChecked;       
                    },
                    function (errror) {
                        instance.setPreLoader(true);
                    }
                );
            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal("hide");
                instance.$hub.$emit("reloadDataTable");
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            }
        }
    };
</script>