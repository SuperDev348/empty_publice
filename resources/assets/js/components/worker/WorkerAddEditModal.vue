<template>
    <div id="worker-modal">
        <div class="modal-layout-header">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-10">
                        <h4 class="m-0" v-if="id">{{ $t('lang.update_worker') }}</h4>
                        <h4 class="m-0" v-else>{{ $t('lang.add_worker') }}</h4>
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
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div v-else class="modal-layout-content service-nav-tab">
            <form>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">                      
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="first_name">{{ $t('lang.first_name') }}</label>                                
                                <input v-model="first_name"
                                       v-validate="'required'"
                                       name="first_name"
                                       id="first_name"
                                       type="text"
                                       class="form-control"/>
                                 <div v-show="isSubmitted && errors.has('first_name')" class="heightError">
                                    <small class="text-danger">
                                        {{ errors.first('first_name')}}
                                    </small>
                                </div>
                            </div>  
                              <div class="form-group col-md-4">
                                <label for="last_name">{{ $t('lang.last_name') }}</label>                                
                                <input v-model="last_name"
                                       v-validate="'required'"
                                       name="last_name"
                                       id="last_name"
                                       type="text"
                                       class="form-control"/>
                                 <div v-show="isSubmitted && errors.has('last_name')" class="heightError">
                                    <small class="text-danger">
                                        {{ errors.first('last_name')}}
                                    </small>
                                </div>
                            </div>                  
                            <div class="form-group col-md-4">
                                <label for="role">{{ $t('lang.worker_role') }}</label>                                
                                <input v-model="role"
                                       v-validate="'required'"
                                       name="role"
                                       id="role"
                                       type="text"
                                       class="form-control"/>
                                 <div v-show="isSubmitted && errors.has('role')" class="heightError">
                                    <small class="text-danger">
                                        {{ errors.first('role')}}
                                    </small>
                                </div>
                            </div>                                                    
                        </div>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-12">
                        <button class="btn app-color mobile-btn"
                                type="submit"
                                @click.prevent="save()">
                            {{$t('lang.save') }}
                        </button>
                        <button class="btn cancel-btn mobile-btn"
                                data-dismiss="modal"
                                @click.prevent>
                            {{$t('lang.cancel') }}
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
        extends: axiosGetPost,
        props: ["id", "modalID", "precision"],
        data() {
            return {
                first_name: '',
                last_name: '',
                role: '',
                isSubmitted: false,
                is_disabled: false,               
                preloaderType: "",
                hidePreloader: false,
                activation: "",
                error: false,
            };
        },
        created() { 
            this.getSingleData();
        },
        mounted() { },
        watch: {
            first_name: function () {
                this.checkValidation();
            },
            last_name: function () {
                this.checkValidation();
            },
            role: function () {
                this.checkValidation();
            }          
        },
        methods: {           
            setPreloader: function (type, action) {
                this.preloaderType = type;
                this.hidePreloader = action;
            },
            save(e) {
                let instance = this;
                instance.isSubmitted = true;

                instance.$validator.validateAll().then(result => {
                    if ( result  ) {
                        let formData = new FormData();
                        formData.append("first_name", instance.first_name);
                        formData.append("last_name", instance.last_name);                
                        formData.append("role", instance.role);                

                        if (instance.id) {                            
                            instance.postDataMethod(
                                "/workerid/" + instance.id,
                                formData
                            );
                        } else {
                            instance.postDataMethod(
                                "/workernew/create/store",
                                formData
                            );
                        }                        
                        //instance.isSubmitted = false;
                        //window.location.reload();
                    }
                });
            },
            postDataThenFunctionality(response) {
                let instance = this;
                $(instance.modalID).modal("hide");
                instance.$hub.$emit("reloadDataTable");
            },
            postDataCatchFunctionality(error) {
                let instance = this;
            },
            checkValidation(submit = false) {
                let instance = this;
                if (submit) {
                    instance.isSubmitted = submit;
                }             
                instance.$validator.validateAll().then(result => {
                    if ( result || !instance.isSubmitted ) {
                        instance.isNavDisable = false;
                    } else {
                        instance.isNavDisable = true;
                    }
                });
            },    
            getWorkerData(route) {
                let instance = this;
                this.setPreLoader(false);
                this.axiosGet(
                    route,
                    function (response) {
                        instance.first_name = response.data.first_name;
                        instance.last_name = response.data.last_name;
                        instance.role = response.data.role;
                        instance.setPreLoader(true);
                    },
                    function (errror) {
                        instance.setPreLoader(true);
                    }
                );                                        
            },
            getSingleData() {
                let instance = this;
                if (instance.id != '') {
                    this.getWorkerData('/get-worker/'+ instance.id);
                }
            }
        }
    };
</script>