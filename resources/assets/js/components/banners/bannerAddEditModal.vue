<template>
  <div>
    <div class="modal-layout-header">
      <div class="container-fluid p-0">
        <div class="row">
          <div class="col-10">
            <h4 class="m-0" v-if="id">{{ $t('lang.update_service') }}</h4>
            <h4 class="m-0" v-else>{{ $t('lang.add_service') }}</h4>
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
        <li class="nav-item"></li>
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#details">{{ $t('lang.details') }}</a>
        </li>
      </ul>

      <form enctype="multipart/form-data">
        <div class="tab-content service-tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="details">
            <div class="row mt-3">
              <div class="form-group col-6">
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
             <div class="row mt-3">
              <div class="img-drag-wrapper mx-auto mt-3">
                <label class="custom-file-label" for="file">
                  <i class="las la-cloud-upload-alt la-4x" />
                  <br />Image
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
      activation: "1",
      isNavDisable: false,
      isSubmitted: false,
      url: null,
      file: "",
 
    };
  },
  created() {
    if (this.id) {
      this.getData("/getbanner/" + this.id);
    }  
  },
  mounted() {},
  watch: {
    title: function () {
      this.checkValidation();
    },
 
  },
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
    } ,
    getData(route) {
      let instance = this;
      this.setPreLoader(false);
      this.axiosGet(
        route,
        function (response) {
          console.log(response);
          instance.title = response.data.title;
          instance.activation = response.data.activation;
   instance.setPreLoader(true);
   
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
        formData.append("title", instance.title);
        formData.append("activation", instance.activation);
 
        if (instance.file != "") {
          formData.append("uploads", instance.file);
        }
 
        if (instance.id) {
          instance.postDataMethod(
            "/updatebanner/" + instance.id,
            formData
          );
        } else {
          instance.postDataMethod("/addbanner", formData);
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

      instance.$validator.validateAll().then((result) => {
        if (!result) {
          instance.isNavDisable = true;
        } else {
          instance.isNavDisable = false;
        }
      });
    },

    getAllCategory(route) {
      let instance = this;
      instance.setPreLoader(false);
      instance.axiosGet(
        route,
        function (response) {
          console.log(response);
          instance.categories = response.data;
          instance.setPreLoader(true);
        },
        function (error) {
          instance.setPreLoader(true);
        }
      );
    },
  },
};
</script>