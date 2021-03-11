<template>
  <div>
    <pre-loader v-if="!hidePreLoader" />

    <div class="service-book-area" v-else>
      <div id="carouselSlider" class="carousel slide" data-ride="carousel">
        <!-- The slideshow -->
        <div class="carousel-inner">
          <div
            :class="index==0?'carousel-item active':'carousel-item'"
            v-for="(banner,index) in banners"
            :key="index"
            :value="banner.id"
          >
            <img :src="publicPath+'/files/'+banner.image" class="carousel-img" />
          </div>
          {{}}
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#carouselSlider" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#carouselSlider" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
      </div>
    </div>
    <div class="container mt-5">
      <div id="searchBar">
        <div class="inner-form">
          <div class="input-field first-wrap">
            <div class="icon-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path
                  d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                />
              </svg>
            </div>
            <input v-model="search" type="text" placeholder="What are you looking for?" />
          </div>

          <div class="input-field fouth-wrap">
            <select name="choices-single-defaul" v-model="selectedCategory">
              <option selected value>ALL</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >{{category.name}}</option>
            </select>
          </div>
          <div class="input-field fouth-wrap">
            <select name="choices-single-defaul" v-model="selectedCity">
              <option selected value>ALL</option>
              <option
                v-for="city in cities"
                :key="city.NameEn"
                :value="city.id">
                {{isEnglish()?city.NameEn:city.NameAr}}</option>
            </select>
          </div>
          <div class="input-field fifth-wrap">
            <button class="btn-search" type="button" v-on:click="searchInvendors()">SEARCH</button>
          </div>
        </div>
      </div>
      <div id="category" class="mt-5">
        <div class="container text-center my-3">
          <!-- <h2 class="font-weight-light">Bootstrap 4 - Multi Item Carousel</h2> -->
          <div class="row mx-auto my-auto">
            <div id="categoryCarousel" class="carousel slide w-100" data-ride="carousel">
              <div class="carousel-inner w-100" role="listbox">
                <div
                  :class="index==0?'carousel-item  active':'carousel-item '"
                  v-for="(category,index) in categories"
                  :key="index"
                  :value="category.id"
                >
                  <div class="col-md-3">
                    <div
                      class="card mb-2 card-transparent card-transparent card-category"
                      :data-id="category.id"
                    >
                      <img
                        class="card-img-top"
                        :src="publicPath+'/files/' +category.icon"
                        alt="Card image cap"
                        :data-id="category.id"
                      />
                      <div class="card-body">
                        <h4 class="card-title" v-if="isEnglish()">{{category.name}}</h4>
                        <h4 class="card-title" v-else>{{category.nameAR}}</h4>
                        <p class="card-text"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <a
                class="carousel-control-prev w-auto"
                href="#categoryCarousel"
                role="button"
                data-slide="prev"
              >
                <span
                  class="carousel-control-prev-icon bg-dark border border-dark rounded-circle"
                  aria-hidden="true"
                ></span>
                <span class="sr-only">Previous</span>
              </a>
              <a
                class="carousel-control-next w-auto"
                href="#categoryCarousel"
                role="button"
                data-slide="next"
              >
                <span
                  class="carousel-control-next-icon bg-dark border border-dark rounded-circle"
                  aria-hidden="true"
                ></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div id="clients" class="mt-5">
        <pre-loader v-if="!hidePreLoader" />
        <div class="row" v-else>
          <div class="col-md-4" v-for="(vendor, index) in filtredVendors" :key="index">
            <div class="card mb-2">
              <img
                class="card-img-top"
                :src="publicPath+'/files/' +vendor.image"
                alt="Card image cap"
              />
              <div class="card-body">
                <h4 class="card-title">
                  <span>{{vendor.name}}</span>
                </h4>
                <p class="card-text">{{vendor.address}}</p>
                <a v-bind:href="'/book/'+vendor.id" class="stretched-link"></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import "popper.js";
import axiosGetPost from "../../helper/axiosGetPostCommon";
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import allLocales from "@fullcalendar/core/locales-all";
import { VueTelInput } from "vue-tel-input";
import {
  setServiceIdToCookie,
  getServiceIdFromCookie,
  deleteServiceIdFromCookie,
} from "./helper/SalonLandingCookieSet.js";

export default {
  extends: axiosGetPost,
  props: [
    "landing_page_header",
    "landing_page_message",
    "payment_methods",
    "user",
    "checksignup",
    "banners",
  ],
  components: {
    FullCalendar,
    VueTelInput,
  },
  data() {
    return {
      isActiveFields: false,
      categories: [],
      selectedCategory: "",
      vendors: [],
      filtredVendors: [],
      search: "",
      cities: [],
      selectedCity: "",
      isTop: true,
      hidePreLoader: false,
      hideCalenderLoader: true,
      continuePreLoader: false,
      timePreLoader: true,
      submitPreLoader: true,
      disable: false,
      onlyonce: true,
    };
  },
  created() {
    console.log(this.banners);
    this.getCategories();
    this.getAllvendors();
       this.getAllCities("/getCities");
  },
  async mounted() {
    $("#categoryCarousel").carousel({
      interval: 3000,
    });
  },
  watch: {},
  computed: {
    // filtredVendors:function(){
    //   let instance = this;
    //   console.log(instance.search);
    //  return instance.vendors.filter((vendor)=>{
    //    return vendor.name.match(instance.search);
    //    });
    // }
  },
  updated: function () {
    this.$nextTick(() => {
      if (this.onlyonce) {
        $("#categoryCarousel .carousel-item").each(function () {
          var minPerSlide = 4;
          var next = $(this).next();
          if (!next.length) {
            next = $(this).siblings(":first");
          }
          next.children(":first-child").clone().appendTo($(this));

          for (var i = 0; i < minPerSlide; i++) {
            next = next.next();
            if (!next.length) {
              next = $(this).siblings(":first");
            }

            next.children(":first-child").clone().appendTo($(this));
          }
        });
        this.onlyonce = false;

        $(this.$el)
          .find(".card-category")
          .on("click", (e) => {
            const categoryId = $(e.currentTarget).data("id");
            this.select(categoryId);
          });
      }
    });
  },
  methods: {
    isEnglish() {
      return this.$root.isLangInglish();
    },
    searchInvendors() {
      let instance = this;

      var catg = (instance.filtredVendors = instance.vendors.filter(
        (vendor) => {
          return (
            vendor.name.match(instance.search) &&
            vendor.category_id.match(instance.selectedCategory)
          );
        }
      ));
    },
    checkIsTop(id) {
      if (id % 2 == 1) {
        var temp = this.isEmpty;
        this.isEmpty = !this.isEmpty;
        return temp;
      } else {
        return this.isEmpty;
      }
    },
    select: function (id) {
      let instance = this;
      instance.hidePreLoader = false;
      // instance.getvendorsBycategory(id);

      instance.selectedCategory = id;
      instance.search = "";
      instance.searchInvendors();
      event.preventDefault();

      $("html, body").animate(
        {
          scrollTop: $("#clients").offset().top - 100,
        },
        800,
        function () {}
      );
      instance.hidePreLoader = true;
    },
    isEmpty(obj) {
      return _.isEmpty(obj) ? true : false;
    },

    getCategories() {
      let instance = this;
      instance.hidePreLoader = false;
      instance.axiosGet(
        "/getAllCategory",
        function (response) {
          instance.categories = response.data;
          instance.hidePreLoader = true;
        },
        function (error) {
          instance.hidePreLoader = true;
        }
      );
    },

    getAllvendors() {
      let instance = this;
      instance.isTop = true;
      instance.hidePreLoader = false;
      instance.axiosGet(
        "/getallvendors",
        function (response) {
          instance.vendors = response.data;
          instance.filtredVendors = response.data;

          instance.hidePreLoader = true;
        },
        function (error) {
          instance.hidePreLoader = true;
        }
      );
    },
    getAllCities(route) {
      let instance = this;
        instance.hidePreLoader = false;
     
      instance.axiosGet(
        route,
        function (response) {
          console.log(response);
          instance.cities = response.data;
          instance.hidePreLoader = true;
        },
        function (error) {
            instance.hidePreLoader = true;
         
        }
      );
    },
  },
};
</script>
 