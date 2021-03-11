<template>
  <div>
    <div class="modal-layout-header">
      <div class="container-fluid p-0">
        <div class="row">
          <div class="col-10">
            <h4 class="m-0" v-if="id">{{ $t('lang.update_product') }}</h4>
            <h4 class="m-0" v-else>{{ $t('lang.add_product') }}</h4>
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
          <a class="nav-link active" data-toggle="tab" href="#details">{{ $t('lang.details') }}</a>
        </li>
        <li v-if="businessType == 'salon'" class="nav-item">
          <a
            class="nav-link"
            :class="{'disabled' : isNavDisable}"
            data-toggle="tab"
            href="#gallery"
            @click="checkValidation(true)"
          >{{ $t('lang.gallery') }}</a>
        </li>
      </ul>

      <form enctype="multipart/form-data">
        <div class="tab-content service-tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="details">
            <div class="row mt-3">
              <div class="form-group col-12">
                <label for="title">{{ $t('lang.title') }}</label>
                <input
                  v-model="title"
                  v-validate="'required'"
                  name="title"
                  id="title"
                  type="text"
                  class="form-control"
                />
                <div v-show="isSubmitted && errors.has('title')" class="heightError">
                  <small class="text-danger">{{ errors.first('title')}}</small>
                </div>
              </div>
              <div class="form-group col-12">
                <label for="description">{{ $t('lang.description') }}</label>
                <textarea
                  id="description"
                  v-model="description"
                  class="form-control service-description"
                />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="quantity">{{ $t('lang.quantity') }}</label>
                <input
                  v-model="quantity"
                  name="availableSpace"
                  id="quantity"
                  type="text"
                  v-validate="'required'"
                  class="form-control"
                  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"
                />
                <div v-show="isSubmitted && errors.has('availableSpace')" class="heightError">
                  <small
                    class="text-danger"
                  >{{$t('lang.available_space_space_field_is_required')}}</small>
                </div>
              </div>
              <div class="form-group col-lg-6">
                <label for="price">{{$t('lang.price_per_product') }}</label>
                <input
                  v-model="price"
                  name="price"
                  id="price"
                  type="text"
                  v-validate="'required'"
                  onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46"
                  class="form-control"
                />
                <div v-show="isSubmitted && errors.has('price')" class="heightError">
                  <small class="text-danger" v-show="errors.has('price')">{{ errors.first('price')}}</small>
                </div>
              </div>
            </div>

            <div class="row">
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
                {{ $t('lang.drag_and_drop_an_image') }}
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
      title: "",
      price: "",
      description: "",
      activation: "1",
      quantity: "1",
      isSubmitted: false,
      is_disabled: false,
      preloaderType: "",
      hidePreloader: false,
      isActiveFields: false,
      error: false,
      users: [],
      url: null,
      file: "",
      isNavDisable: false,
    };
  },
  created() {
      if (this.id) {
      this.getProductData("/getproduct/" + this.id);
    }
  },
  mounted() {
 
  },
  watch: {
    title: function () {
      this.checkValidation();
    },
    quantity: function () {
      this.checkValidation();
    },
    price: function () {
      this.checkValidation();
    },
  },
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
    },
    
       getProductData(route) {
      let instance = this;
      this.setPreLoader(false);
      this.axiosGet(
        route,
        function (response) {
            console.log(response);
          instance.title = response.data.title;
          instance.price = response.data.price;
          instance.description = response.data.description;
          instance.activation = response.data.activation;
          instance.quantity = response.data.quantity;
           instance.setPreLoader(true);
        },
        function (error) {
          console.log(error);
          instance.setPreLoader(true);
        }
      );
    },
     
    setPreloader: function (type, action) {
      this.preloaderType = type;
      this.hidePreloader = action;
    },
    save(e) {
      let instance = this;
      instance.isSubmitted = true;
      instance.$validator.validateAll().then((result) => {
        if (
          result  
        ) {
          let formData = new FormData();
          formData.append("title", instance.title);
          formData.append(
            "price",
            accounting.unformat(instance.price, instance.decimalSeparator)
          );
          formData.append("quantity", instance.quantity);
          formData.append("activation", instance.activation);
          formData.append("description", instance.description);
          if (instance.file != "") {
            formData.append("uploads", instance.file);
          }

          if (instance.id) {
            instance.postDataMethod("/editproduct/" + instance.id, formData);
          } else {
            instance.postDataMethod("/addproduct", formData);
          }
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
  },
};
</script>