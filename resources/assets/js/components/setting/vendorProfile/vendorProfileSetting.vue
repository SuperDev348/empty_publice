<template>
  <div class="main-layout-card">
    <div class="main-layout-card-header">
      <div class="main-layout-card-content-wrapper">
        <div class="main-layout-card-header-contents">
          <h5 class="bluish-text no-margin">Profile</h5>
        </div>
      </div>
    </div>
    <pre-loader v-if="!hidePreLoader"></pre-loader>
    <div class="main-layout-card-content" v-else>
           <div class="mb-4">
                    <h6 class="app-heading-color">
                        {{ $t('lang.invoice_settings') }}
                    </h6>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title">title</label>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   v-model="title">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="about">about</label>
                   
                                   <textarea v-model="about" rows="6" id="about" class="form-control" placeholder="add multiple lines"></textarea>
                        </div>
                
                    </div>
                </div>
      <div class="pl-3">
        <div class="row mb-3">
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
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />banner1
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input
                id="urlfileBanner1"
                type="file"
                class="custom-file-input"
                @change="onFileChangeBanner1"
              />
              <div id="preview">
                <img v-if="urlfileBanner1" :src="urlfileBanner1" alt />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />banner2
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input
                id="urlfileBanner2"
                type="file"
                class="custom-file-input"
                @change="onFileChangeBanner2"
              />
              <div id="preview">
                <img v-if="urlfileBanner2" :src="urlfileBanner2" alt />
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="img-drag-wrapper mx-auto mt-3">
              <label class="custom-file-label" for="file">
                <i class="las la-cloud-upload-alt la-4x" />
                <br />banner3
                <br />
                <span>{{ $t('lang.browse_to_choose_a_file') }}</span>
                <br />
                <small class="font-italic">({{ ($t('lang.recommended_service_image')) }})</small>
              </label>
              <input
                id="urlfileBanner3"
                type="file"
                class="custom-file-input"
                @change="onFileChangeBanner3"
              />
              <div id="preview">
                <img v-if="urlfileBanner3" :src="urlfileBanner3" alt />
              </div>
            </div>
          </div>
        </div>

        <div class="row" v-if="!is_admin">
          <button
            class="btn btn-primary app-color mobile-btn"
            @click.prevent="save()"
            type="submit"
          >
            {{
            $t('lang.save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosGetPost from "../../../helper/axiosGetPostCommon";

export default {
  extends: axiosGetPost,
  data() {
    return {
         title: "",
         about: "",
           url: null,
      urlfileBanner1: null,
      urlfileBanner2: null,
      urlfileBanner3: null,
       file: "",
       fileBanner1: "",
       fileBanner2: "",
       fileBanner3: "",
      hidePreLoader: true,
      is_admin: false,
    };
  },
  created() {
    this.readData();
  },
  methods: {
    setPreloader: function (type, action) {
      this.preloaderType = type;
      this.hidePreloader = action;
    },
    readData() {
      let instance = this;
      instance.hidePreLoader = false;
      instance.axiosGet(
        "/vendorprofileData",
        function (response) {
   
          instance.hidePreLoader = true;
          instance.is_admin = response.data.is_admin;
          instance.title = response.data.title;
          instance.about = response.data.about;
 
        },
        function (error) {
          instance.hidePreLoader = true;
        }
      );
    },
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
    save() {
      let instance = this;
     let formData = new FormData();
      formData.append("title", instance.title);
      formData.append("about", instance.about);
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
      instance.hidePreLoader = false;
      instance.axiosPost(
        "/updateMyProfile",
        formData,
        function (response) {
          instance.hidePreLoader = true;
        },
        function (error) {
          instance.errors = error.data.errors;
          instance.hidePreLoader = true;
        }
      );
    },
  },
};
</script>
