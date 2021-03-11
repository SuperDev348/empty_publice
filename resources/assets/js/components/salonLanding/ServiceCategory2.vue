<template>
    <div>
   <pre-loader v-if="!hidePreLoader"/>
        <div class="service-book-area" v-else>

            <div class="main-banner"
                 :style="{'background-image': 'url(' + publicPath+'/images/' + 'background/background-image.jpeg' + ')'}">
                <div class="container">
                    <div class="text-white">
                        <h1 class="mb-0 banner-title">{{ landing_page_header }}</h1>
                        <p v-if="landing_page_message != ''" class="mb-4 banner-subtitle">
                            {{ landing_page_message }}
                        </p>
                    </div>
                
                </div>
            </div>
        <section class="content-central">
                <!-- Shadow Semiboxed Layout -->
                <div class="semiboxshadow text-center">
                    <img src="images/xx" class="img-responsive" alt="">
                </div>
                <!-- End Shadow Semiboxed Layout -->

                <!-- End content info - Services Items-->
                <div class="content_info" id="services">
                    <div class="content_resalt">
                        <div class="container">
                            <div class="row">
                                <!-- Info Resalt - Info Services -->
                                <div class="col-md-4 ">
                                    <div class="services-lines-info">
                                        <h2>WELCOME TO BOOKING</h2>
                                        <p class="lead">
                                            Book apointment and make payment facilities.
                                            <span class="line"></span>
                                        </p>

                                        <p>Find a wide variety of Booking  Hospitals, SPA, Clinics, Salons and more in www.com.You can choose your favorite services and start planning your appointment. You can also check availability of appointment. </p>
                                    </div>
                                </div>   
                                <!-- End Info Resalt - Info Services --> 

                                <!-- Services Items -->
                                <div class="col-md-8">
                                    <ul class="services-lines">
                                        <li v-for="category in categories" :key="category.id" :value="category.id">
                                            <div class="item-service-line" v-on:click="select(category.id)">
                                            <i v-bind:class="[ category.icon ]" ></i>
                                                <h5>{{category.name}}</h5>
                                            </div>
                                        </li>


                                    </ul>
                                </div> 
                                <!-- End Services Items --> 
                            </div>
                        </div>
                    </div>
                </div>   
              <div class="content_info">
                    <!-- Title -->
                    <div class="container">
                        <div class="row">
                            <div class="titles">
                                <h2>Our <span>CLIENTS</span> </h2>
                                <i class="fa fa-bicycle"></i>
                                <hr class="tall">
                            </div>                    
                        </div>
                    </div>
                    <!-- End Title-->

                    <!-- content-boxes-->
                    <div class="content-boxes" id="contact">
                    

                        <!-- Item-boxe-->
                        <div class="item-boxed"  v-for="(vendor, index) in vendors"  :key="index">
                            <!-- odd *******************************************************-->
                            <div v-if="checkIsTop(index)">
                            <div class="info-boxed boxed-bottom">
                                <h3>
                                   {{vendor.name}}<br>
                                    <span>{{vendor.address}}</span>
                                </h3>
                                <hr class="separator">
                                <p>The Golden Bay, heated and opened in 2003, with a total of 380 rooms.</p>
                                <ul class="starts">
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
                                </ul>
                                <div class="content-btn"><a v-bind:href="'/book/'+vendor.id" class="btn btn-primary">Book Now</a></div>
                              
                            </div>
                            <div  class="image-boxed image-bottom">
                                <span class="overlay"></span>
                                 <img src="images/5.jpg" class="img-responsive" alt="">
                                <a href="/book" class="more-boxe"><i class="fa fa-plus-circle"></i></a>
                            </div>
                            </div>
                        

                        <!-- event  ************************************-->
                         <div v-else>
                         <div     class="image-boxed">
                                <span class="overlay"></span>
                                  <img src="images/2.jpg" class="img-responsive" alt="">
                                <a href="/book" class="more-boxe"><i class="fa fa-plus-circle"></i></a>
                            </div>
                            <div   class="info-boxed boxed-top">
                                <h3>
                                   {{vendor.name}}<br>
                                    <span>{{vendor.address}}</span>
                                </h3>
                                <hr class="separator">
                                <p>The Royal National is in London near Covent Garden.</p>
                                <ul class="starts">
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    <li><a href="#"><i class="fa fa-star-half-empty"></i></a></li>
                                </ul>
                                <div class="content-btn"><a  v-bind:href="'/book/'+vendor.id" class="btn btn-primary">Book Now</a></div>
                             </div>
                            </div>
                            </div>
                        <!-- End Item-boxe-->

                        
                    </div>
                    <!-- End content-boxes-->
                </div>
        </section>
    
    </div>

              <div id="services"  class="service-list-area"></div>
  
        </div>
</template>
<script>
    import 'popper.js';
    import axiosGetPost from '../../helper/axiosGetPostCommon';
    import FullCalendar from '@fullcalendar/vue';
    import dayGridPlugin from '@fullcalendar/daygrid';
    import interactionPlugin from "@fullcalendar/interaction";
    import allLocales from '@fullcalendar/core/locales-all';
    import {VueTelInput} from 'vue-tel-input';
    import {
        setServiceIdToCookie,
        getServiceIdFromCookie,
        deleteServiceIdFromCookie
    } from './helper/SalonLandingCookieSet.js';

    export default {
        extends: axiosGetPost,
        props: ['landing_page_header', 'landing_page_message', 'payment_methods', 'user', 'checksignup'],
        components: {
            FullCalendar,
            VueTelInput
        },
        data() {
            return {
                
                isActiveFields: false,
                categories: [],
                categories: [],
                vendors: [],
                isTop:true,
                hidePreLoader: true,
                hideCalenderLoader: true,
                continuePreLoader: false,
                timePreLoader: true,
                submitPreLoader: true,
                disable: false,
 
            }
        },
        created() {
            this.getCategories();
         
        },
        mounted() {
            
        },
        watch: {
       
        },
        methods: {
            checkIsTop(id){
             if(id%2==1){
                 var temp=this.isEmpty;
                 this.isEmpty=! this.isEmpty;
                 return temp;
             }else{
                 return this.isEmpty; 
             }
            },
          select: function(id) {
            let instance = this;
              instance.getvendorsBycategory(id);
                event.preventDefault();
                   $('html, body').animate({
                    scrollTop: $("#contact").offset().top-100
                    }, 800, function(){
                      
                });
             
        },
            isEmpty(obj){
                return _.isEmpty(obj) ? true : false;
            },
           
            setLandingPageFromCookie(){
             /*   if(this.cookieData.serviceId == this.service_id ){
                    this.startDay = this.cookieData.date;
                    this.serviceTiming = this.cookieData.serviceTiming;
                    this.availableSeatsForTiming = this.cookieData.availableSeatsForTiming;
                    this.selectTime = this.cookieData.selectTime;
                    this.full_name = this.cookieData.full_name;
                    this.email = this.cookieData.email;
                    this.phone_number = this.cookieData.phone_number;
                    
                    if(this.selectTime.length) this.isCalendarSet = true;
                    if(this.cookieData.noOfAdult) this.noOfAdult = this.cookieData.noOfAdult;
                    if(this.cookieData.noOfChildren) this.noOfChildren = this.cookieData.noOfChildren;
                    if(this.serviceTiming.length > 0)this.isTime = true;
                    if(this.cookieData.isBookForm)this.bookingForm()
                }*/
            },

           
            getCategories() {
                let instance = this;
                    instance.continuePreLoader = true;
                    instance.axiosGet('/getAllCategory',
                        function (response) {
                           instance.categories= response.data;
                            instance.continuePreLoader = false;
                        },
                        function (error) {
                            instance.continuePreLoader = false;
                        });
              
            },

            getvendorsBycategory(id){
                      let instance = this;
                      instance.isTop=true;
                    instance.continuePreLoader = true;
                    instance.axiosGet('/getvendorsByCategory/'+id,
                        function (response) {
                           instance.vendors= response.data;
                           console.log(instance.vendors);
                            instance.continuePreLoader = false;
                        },
                        function (error) {
                            instance.continuePreLoader = false;
                        });

            },
             
           },
    }
</script>
