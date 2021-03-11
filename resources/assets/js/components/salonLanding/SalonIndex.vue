<template>
  <div class="salon-landing-wrapper">
    <!-- Loader -->
    <div class="loader">
      <div class="loader_div"></div>
    </div>

    <!-- Header -->
    <header class="main_header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-2 col-sm-3 col-xs-6 col-5">
            <div class="logo">
              <a href="/">
                <img :src="publicPath+'/uploads/logo/'+appLogo" alt class="logo" />
              </a>
            </div>
          </div>
          <div class="col-lg-10 col-sm-9 col-xs-6 col-7">
            <div class="main_nav ml-auto">
              <!-- Main Nav Menu -->
              <nav id="primary_navigation" class="site_navigation">
                <div class="main_menu">
                  <a href="javascript:void(0);" class="close_menu">
                    <i class="fa fa-times"></i>
                  </a>
                  <ul class="nav main_menu_list justify-content-end">
                    <li class="menu-item page-active">
                      <i class="fa fa-home"></i>&nbsp;<a href="#home">Home</a>
                    </li>
                    <li class="menu-item">
                      <i class="fa fa-info-circle"></i>&nbsp;<a href="#about">About</a>
                    </li>

                    <li class="menu-item">
                      <i class="fa fa-cog"></i>&nbsp;<a href="#service">services</a>
                    </li>

                    <li v-if="user === null && checksignup.can_signup === 1" class="menu-item">
                      <i class="fa fa-user"></i>&nbsp;<a :href="publicPath+'/register'">{{ $t('lang.sign_up') }}</a>
                    </li>
                    <li v-if="user === null" class="menu-item">
                      <i class="fa fa-sign-in"></i>&nbsp;<a :href="publicPath+'/login'">{{ $t('lang.sign_in') }}</a>
                    </li>
                    <li v-if="user" class="menu-item">
                      <a href="#" @click="dashboard">
                        <i class="fa fa-user"></i>&nbsp;<span>{{ $t('lang.my_account') }}</span>
                      </a>
                    </li>
                    <li v-if="user" class="menu-item">
                      <a href="#" @click="logout">
                        <i class="fa fa-sign-out"></i>&nbsp;<span>{{ $t('lang.logout_nv') }}</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </nav>
              <!-- End Main Nav Menu -->
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- /. Header -->
    <!-- banner Section -->
    <section class="banner_sec" id="home">
      <div class="banner_slider">
        <div
          class="b_slide"
          v-for="banner in banners"
          :key="banner"
          :style="{'background-image': 'url(' + publicPath+'/files/' + banner + ')'}"
        >
          <div class="container">
            <div class="banner_caption">
              <div class="row">
                <div class="col-sm-9">
                  <h3 data-animation="fadeInLeft" data-delay="0.5s">{{title}}</h3>
                  <h1 data-animation="fadeInLeft" data-delay="0.9s">{{name}}</h1>
                  <p data-animation="fadeInLeft" data-delay="1.3s">{{about}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /. banner Section -->

    <!-- About Section -->
    <section class="about_sec" id="about">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h2 class="sec_heading wow fadeInLeft">
              <span>About Us</span>Our values and goals
            </h2>
            <p class="wow fadeInLeft">
              Lorem Ipsum is simply dummy text of the printing and typesetting industry.
              Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown
              printer took a galley of type and scrambled it to make a type specimen book. It has survived not
              only five centuries, but also the leap into electronic typesetting, remaining essentially
              unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem
              Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker
              including versions of Lorem Ipsum.
            </p>
          </div>
          <div class="col-md-6">
            <div class="about_right wow fadeInRight">
              <img src="/images/about-img.jpg" alt="about" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /. About Section -->

    <pre-loader v-if="!fullPageLoader" />
    <div v-else>
      <!--Service booking area-->
      <service_booking_landing
        :user="user"
        :payment_methods="paymentMethods"
        :landing_page_header="landing_page_header"
        :landing_page_message="landing_page_message"
        :checksignup="checksignup"
        :service="service"
        @serviceLength="serviceLength"
      />
      <order_products_landing
        :user="user"
        :payment_methods="paymentMethods"
        :checksignup="checksignup"
        :service="service"
        @serviceLength="serviceLength"
      />

      <!--Footer area-->
      <div id="contact" class="footer-section">
        <!--footer bottom-->
        <div class="footer-bottom-section">
          <div class="container">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <ul
                  class="nav justify-content-center justify-content-md-start justify-content-lg-start footer-nav-menu"
                >
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      href="#home"
                      @click="smoothScrollToSectionFromFooter('#home')"
                    >{{ $t('lang.home') }}</a>
                  </li>
                  <li class="nav-item" v-show="!hideService">
                    <a
                      class="nav-link"
                      href="#services"
                      @click="smoothScrollToSectionFromFooter('#services')"
                    >{{ $t('lang.services') }}</a>
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      href="#contact"
                      @click="smoothScrollToSectionFromFooter('#contact')"
                    >{{ $t('lang.contact') }}</a>
                  </li>

                  <li v-if="user === null && checksignup.can_signup === 1" class="nav-item">
                    <a class="nav-link" href="register">{{ $t('lang.sign_up') }}</a>
                  </li>
                  <li v-if="user === null" class="nav-item">
                    <a class="nav-link" href="login">{{ $t('lang.sign_in') }}</a>
                  </li>

                  <li v-if="user" class="nav-item">
                    <a class="nav-link" href="#" @click="dashboard">{{ $t('lang.my_account') }}</a>
                  </li>
                  <li v-if="user" class="nav-item">
                    <a class="nav-link" href="#" @click="logout">{{ $t('lang.logout_nv') }}</a>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                <ul
                  class="nav justify-content-center justify-content-md-end justify-content-lg-end text-white footer-nav-social"
                >
                  <li v-if="custom_content !== null" class="nav-item">
                    <a
                      class="nav-link service-policy"
                      href="#"
                      data-toggle="modal"
                      data-target="#servicePolicyModal"
                    >
                      <span
                        :class="{'service-policy-link': custom_content !== null && hasSocialLink }"
                      >Service Policy</span>
                    </a>
                  </li>
                  <li v-if="sociallinkdata.twitter != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.twitter" target="_blank">
                      <i class="lab la-twitter" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.facebook != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.facebook" target="_blank">
                      <i class="lab la-facebook-f" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.youtube != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.youtube" target="_blank">
                      <i class="lab la-youtube" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.linkedin != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.linkedin" target="_blank">
                      <i class="lab la-linkedin-in" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.instagram != null" class="nav-item">
                    <a
                      class="nav-link social-link"
                      :href="sociallinkdata.instagram"
                      target="_blank"
                    >
                      <i class="lab la-instagram" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.google_plus != null" class="nav-item">
                    <a
                      class="nav-link social-link"
                      :href="sociallinkdata.google_plus"
                      target="_blank"
                    >
                      <i class="lab la-google-plus" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.pinterest != null" class="nav-item">
                    <a
                      class="nav-link social-link"
                      :href="sociallinkdata.pinterest"
                      target="_blank"
                    >
                      <i class="lab la-pinterest" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.tumblr != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.tumblr" target="_blank">
                      <i class="lab la-tumblr" />
                    </a>
                  </li>
                  <li v-if="sociallinkdata.flickr != null" class="nav-item">
                    <a class="nav-link social-link" :href="sociallinkdata.flickr" target="_blank">
                      <i class="lab la-flickr" />
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-12">
                <p class="text-white-50 mb-0 copyright-text">{{ copyright_text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Service Policy Modal -->
    <div
      class="modal modal-landing fade"
      id="servicePolicyModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-body justify-content-center">
            <h4
              class="modal-title text-center mb-4"
              id="exampleModalLabel"
            >{{ $t('lang.service_policy') }}</h4>
            <img
              :src="publicPath+'/images/service-policy.png'"
              class="img-fluid mx-auto d-block mb-4"
              width="50%"
              alt
            />
            <div class="text-justify mb-4" v-html="custom_content" />
            <div class="row justify-content-center">
              <div class="col-4">
                <button
                  type="button"
                  class="btn btn-block btn-landing"
                  data-dismiss="modal"
                >{{ $t('lang.close_btn') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axiosGetPost from "../../helper/axiosGetPostCommon";
import {
  setServiceIdToCookie,
  getServiceIdFromCookie,
  deleteServiceIdFromCookie,
} from "./helper/SalonLandingCookieSet.js";

export default {
  extends: axiosGetPost,
  props: [
    "copyright_text",
    "landing_page_header",
    "landing_page_message",
    "sessionstatus",
    "checksignup",
    "user",
    "service",
  ],
  data() {
    return {
      isActive: false,
      modalID: "#add-edit-modal",
      selectedItemId: "",
      custom_content: "",
      email: "",
      subject: "",
      message: "",
      contactInfo: {},
      hasSocialLink: false,
      contactpreloader: false,
      sociallinkdata: {
        facebook: "",
        instagram: "",
        linkedin: "",
        twitter: "",
        google_plus: "",
        youtube: "",
        pinterest: "",
        tumblr: "",
        flickr: "",
      },
      timePreLoader: true,
      submitted: false,
      confirmAlert: false,
      fullPageLoader: true,
      paymentMethods: [],
      hideService: false,
      hasData: (value) => {
        return !_.isEmpty(value);
      },
      isMobileMenuActive: false,
      vendor_id: "",
      banners: [],
      about: "",
      title: "",
      name:"",
      onlyonce: true,
    };
  },
  created() {
    var url = window.location.pathname;
    this.vendor_id = url.substring(url.lastIndexOf("/") + 1);
    this.getvendorData(this.vendor_id);
    this.getContactInfo("/get-contact-info");
    this.getSocialLinkData("/getAllsocialData");
    this.getServicePolicy();
    this.getPaymentMethods();
  },
  updated: function () {
    console.log("updated");
    this.$nextTick(() => {
      console.log("nextTick");
      if (this.onlyonce) {
        $(".banner_slider").slick({
          dots: true,
          infinite: true,
          speed: 2000,
          // fade:true,
          slidesToShow: 1,
          arrows: false,
          autoplay: true,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 767,
              settings: {
                dots: false,
              },
            },
          ],
        });
         this.onlyonce=false;
      }
    });
  },
  methods: {
    getvendorData() {
      let instance = this;
      instance.fullPageLoader = false;
      this.axiosGet(
        "/vendors/getVendor/" + this.vendor_id,
        function (response) {
          console.log(response.data);
          instance.about = response.data.about;
          instance.title = response.data.title;
          instance.name = response.data.name;
          if (response.data.banner1 != "")
            instance.banners.push(response.data.banner1);
          if (response.data.banner2 != "")
            instance.banners.push(response.data.banner2);
          if (response.data.banner3 != "")
            instance.banners.push(response.data.banner3);
          instance.fullPageLoader = true;
        },
        function (error) {
          instance.fullPageLoader = true;
        }
      );
    },
    navigateToHomeSection() {
      $("html, body").animate(
        {
          scrollTop: $("#home").offset().top,
        },
        600,
        function () {
          window.location.hash = "";
        }
      );
    },
    getServicePolicy() {
      let instance = this;
      instance.fullPageLoader = false;
      this.axiosGet(
        "/getfrontservicepolicy",
        function (response) {
          instance.custom_content = response.data.custom_content;
          instance.fullPageLoader = true;
        },
        function (error) {
          instance.fullPageLoader = true;
        }
      );
    },
    save() {
      let instance = this;
      instance.$validator.validateAll().then((result) => {
        if (result) {
          instance.inputFields = {
            email: instance.email,
            subject: instance.subject,
            message: instance.message,
          };
          instance.contactpreloader = true;
          instance.postDataMethod("/contact-us", instance.inputFields);
        }
      });
    },
    getSocialLinkData(route) {
      let instance = this;
      instance.axiosGet(
        route,
        function (response) {
          instance.sociallinkdata = response.data;

          if (
            instance.sociallinkdata.facebook != null ||
            instance.sociallinkdata.instagram != null ||
            instance.sociallinkdata.linkedin != null ||
            instance.sociallinkdata.twitter != null ||
            instance.sociallinkdata.google_plus != null ||
            instance.sociallinkdata.youtube != null ||
            instance.sociallinkdata.pinterest != null ||
            instance.sociallinkdata.tumblr != null ||
            instance.sociallinkdata.flickr != null
          )
            instance.hasSocialLink = true;
        },
        function (error) {}
      );
    },
    getContactInfo(route) {
      let instance = this;
      this.axiosGet(
        route,
        function (response) {
          instance.contactInfo = response.data;
        },
        function (error) {}
      );
    },
    postDataThenFunctionality(response) {
      let instance = this;
      instance.email = "";
      instance.subject = "";
      instance.message = "";
      instance.$validator.reset();
      this.contactpreloader = false;
      instance.confirmAlert = true;
      setTimeout(function () {
        $("#confirmAlert").delay(5000).fadeOut("slow");
      }, 100);
    },
    postDataCatchFunctionality(error) {
      this.$validator.reset();
      this.contactpreloader = false;
    },
    logout() {
      this.redirect("/logout");
      deleteServiceIdFromCookie();
    },
    dashboard() {
      this.redirect("/dashboard");
    },
    smoothScrollToSectionFromFooter(id) {
      $("html, body").animate(
        {
          scrollTop: $(id).offset().top - 60,
        },
        1000,
        function () {
          /*window.location.hash = "";*/
        }
      );
    },
    getPaymentMethods() {
      let instance = this;
      this.axiosGet(
        "/getpmethods",
        function (response) {
          instance.paymentMethods = response.data;
        },
        function (response) {}
      );
    },
    serviceLength(value) {
      if (value < 1) this.hideService = true;
      else this.hideService = false;
    },
    openMobileMenu() {
      this.isMobileMenuActive = true;
      if (window.innerWidth > 667) {
        $("#mobileMenuWrapper").width("300px");
        $("html, body").css("overflowY", "hidden");
      } else {
        $("#mobileMenuWrapper").width("60vw");
        $("html, body").css("overflowY", "hidden");
      }
    },
    closeMobileMenu() {
      $("#mobileMenuWrapper").width("0");
      $("html, body").css("overflowY", "auto");
      this.isMobileMenuActive = false;
    },
  },
};
</script>
