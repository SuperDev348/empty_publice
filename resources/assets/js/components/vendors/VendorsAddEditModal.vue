<template>
  <div>
    <div class="modal-layout-header">
      <div class="container-fluid p-0">
        <div class="row">
          <div class="col-10">
            <h4 class="m-0" v-if="id">{{ trans('lang.update_service') }}</h4>
            <h4 class="m-0" v-else>{{ trans('lang.add_service') }}</h4>
          </div>
          <div class="col-2 text-right">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click.prevent
            >
              <span aria-hidden="true">
                <i class="la la-close" />
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <pre-loader v-if="!hidePreLoader" />
    <div v-else class="modal-layout-content service-nav-tab">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
                 <li class="nav-item">
                    <a class="nav-link active"
                       data-toggle="tab"
                       href="#details">
                        {{ trans('lang.details') }}
                    </a>
                </li>
    
        <li v-if="businessType == 'salon'" class="nav-item">
          <a
            class="nav-link"
            :class="{'disabled' : isNavDisable}"
            data-toggle="tab"
            href="#gallery"
            @click="checkValidation(true)"
          >{{ trans('lang.gallery') }}</a>
        </li>
 
      </ul>

      <form enctype="multipart/form-data">
        <div class="tab-content service-tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="details">
            <div class="row mt-3">
              <div class="form-group col-12">
                <label for="name">{{ trans('lang.name') }}</label>
                <input
                  v-model="name"
                  v-validate="'required'"
                  name="name"
                  id="name"
                  type="text"
                  class="form-control"
                />
                <div v-show="isSubmitted && errors.has('name')" class="heightError">
                  <small class="text-danger">{{ errors.first('name')}}</small>
                </div>
              </div>
            </div>

            <div class="row">
                        <div class="form-group maergin-top col-md-6">
                    <label for="cityName"> City</label>
                    <select v-model="cityName"
                            v-validate="'required'"
                            name="cityName"
                            data-vv-as="cityName"
                            id="cityName"
                            class="custom-select"
                            :class="{ 'is-invalid': errors.has('cityName') }">
                        <option value="" disabled selected>{{ $t('lang.choose_one') }}</option>
                        <option v-for="city in cities" :key="city.NameEn" :value="city.NameEn"> {{city.NameEn}}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('cityName')">
                            {{ errors.first('cityName') }}
                        </small>
                    </div>
                </div>

              <div class="form-group col-md-6">
                <label for="address">{{ $t('lang.address') }}</label>
                <input
                  v-model="address"
                  v-validate="'required'"
                  name="address"
                  id="address"
                  type="text"
                  class="form-control"
                />
                <div v-show="isSubmitted && errors.has('address')" class="heightError">
                  <small class="text-danger">{{ errors.first('address')}}</small>
                </div>
              </div>

            </div>

            <div class="row">
                      <div class="form-group col-md-6">
                <label for="phone">{{ $t('lang.phone') }}</label>
                <input
                  v-model="phone"
                  v-validate="'required'"
                  name="phone"
                  id="phone"
                  type="text"
                  class="form-control"
                />
                <div v-show="isSubmitted && errors.has('phone')" class="heightError">
                  <small class="text-danger">{{ errors.first('phone')}}</small>
                </div>
              </div>
              
                <div class="form-group maergin-top col-md-6">
                    <label for="category_id"> category_id</label>
                    <select v-model="category_id"
                            v-validate="'required'"
                            name="category_id"
                            data-vv-as="category_id"
                            id="category_id"
                            class="custom-select"
                            :class="{ 'is-invalid': errors.has('category_id') }">
                        <option value="" disabled selected>{{ $t('lang.choose_one') }}</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id"> {{category.name}}</option>
                    </select>
                    <div class="heightError">
                        <small class="text-danger" v-show="errors.has('category_id')">
                            {{ errors.first('category_id') }}
                        </small>
                    </div>
                </div>
            </div>
               <div class="row">
              <div class="form-group col-md-6">
                <label for="address">rank</label>
                <input
                  v-model="rank"
                  name="rank"
                  id="rank"
                  type="text"
                  class="form-control"
                />
          
              </div>
                    <div class="form-group col-md-6">
                <label class="d-block">{{ $t('lang.activation') }}</label>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input
                      type="radio"
                      class="form-check-input"
                      v-model="activation"
                      value="1"
                      name="activation"
                    />
                    {{ $t('lang.yes') }}
                  </label>
                </div>
                <div class="form-check-inline">
                  <label class="form-check-label">
                    <input
                      type="radio"
                      class="form-check-input"
                      v-model="activation"
                      value="0"
                      name="activation"
                    />
                    {{ $t('lang.no') }}
                  </label>
                </div>
           
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="gallery">
            <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />
              Image
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input id="file" type="file" class="custom-file-input" @change="onFileChange" />
              <div id="preview">
                <img v-if="url" :src="url" alt />
              </div>
            </div>
                   <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />
              banner1
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input id="urlfileBanner1" type="file" class="custom-file-input" @change="onFileChangeBanner1" />
              <div id="preview">
                <img v-if="urlfileBanner1" :src="urlfileBanner1" alt />
              </div>
            </div>
                   <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />
              banner2
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input id="urlfileBanner2" type="file" class="custom-file-input" @change="onFileChangeBanner2" />
              <div id="preview">
                <img v-if="urlfileBanner2" :src="urlfileBanner2" alt />
              </div>
            </div>
                   <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />
              banner3
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input id="urlfileBanner3" type="file" class="custom-file-input" @change="onFileChangeBanner3" />
              <div id="preview">
                <img v-if="urlfileBanner3" :src="urlfileBanner3" alt />
              </div>
            </div>
          </div>
        </div>
        <div class="form-row mt-2">
          <div class="col-12">
            <button
              class="btn app-color mobile-btn"
              type="submit"
              @click.prevent="save()"
            >{{$t('lang.save') }}</button>
            <button
              class="btn cancel-btn mobile-btn"
              data-dismiss="modal"
              @click.prevent
            >{{$t('lang.cancel') }}</button>
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
      name: "",
      address: "",
      phone: "",
      activation: "1",
      category_id: "",
       categories: [],
       cities: [],
       cityName:"",
      isNavDisable: false,
      isSubmitted: false,
      rank: '999',
      url: null,
      urlfileBanner1: null,
      urlfileBanner2: null,
      urlfileBanner3: null,
       file: "",
       fileBanner1: "",
       fileBanner2: "",
       fileBanner3: "",
    };
  },
  created() {
    if (this.id) {
      this.getVendorData("/vendors/getVendor/" + this.id);
    } 
      this.getAllCategory("/getAllCategory");
      this.getAllCities("/getCities");
     
  },
  mounted() {},
  watch: {
    name: function () {
      this.checkValidation();
    },
    address: function () {
      this.checkValidation();
    },
    phone: function () {
      this.checkValidation();
    },

    category_id: function () {
      this.checkValidation();
    },
        city: function () {
      this.checkValidation();
    }
 
  },
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
    },
    onFileChangeBanner1(e) {
      this.fileBanner1 = e.target.files[0];
      this.urlfileBanner1 = URL.createObjectURL(this.fileBanner1);
    },
    onFileChangeBanner2(e) {
      this.fileBanner2 = e.target.files[0];
      this.urlfileBanner2 = URL.createObjectURL(this.fileBanner2);
    },
    onFileChangeBanner3(e) {
      this.fileBanner3 = e.target.files[0];
      this.urlfileBanner3 = URL.createObjectURL(this.fileBanner3);
    },
    getVendorData(route) {
      let instance = this;
      this.setPreLoader(false);
      this.axiosGet(
        route,
        function (response) {
          instance.name = response.data.name;
          instance.address = response.data.address;
          instance.cityName = response.data.city;
          instance.phone = response.data.phone;
          instance.activation = response.data.activation;
          instance.category_id = response.data.category_id;
          instance.rank = response.data.rank;
        
         
        },
        function (error) {
          console.log(error);
          instance.setPreLoader(true);
        }
      );
    },
    save(e) {
      let instance = this;
      instance.isSubmitted = true;
      instance.$validator.validateAll().then((result) => {
        let formData = new FormData();
        formData.append("name", instance.name);
        formData.append("address", instance.address);
        formData.append("city", instance.cityName);
        formData.append("phone", instance.phone); 
        formData.append("activation", instance.activation);
        formData.append("category_id", instance.category_id);
        formData.append("rank", instance.rank);
        if (instance.file != "") {
          formData.append("uploads", instance.file);
        }
        if (instance.fileBanner1 != "") {
          formData.append("uploadsbanner1", instance.fileBanner1);
        }
        if (instance.fileBanner2 != "") {
          formData.append("uploadsbanner2", instance.fileBanner2);
        }
        if (instance.fileBanner3 != "") {
          formData.append("uploadsbanner3", instance.fileBanner3);
        }
        if (instance.id) {
          instance.postDataMethod(
            "/vendors/updateVendor/" + instance.id,
            formData
          );
        } else {
          instance.postDataMethod("/vendors/addVendor", formData);
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
                    if ( !result) {
                        instance.isNavDisable = true;
                     
                    } else {
                        instance.isNavDisable = false;
                      
                    }
                });
    },

       getAllCategory(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                      console.log(response);
                        instance.categories = response.data;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            },
              getAllCities(route) {
                let instance = this;
                instance.setPreLoader(false);
                instance.axiosGet(route,
                    function (response) {
                      console.log(response);
                        instance.cities = response.data;
                        instance.setPreLoader(true);
                    },
                    function (error) {
                        instance.setPreLoader(true);
                    },
                );
            }
  },
};
</script>
