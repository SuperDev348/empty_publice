<template>
  <div>
    <pre-loader v-if="!hidePreLoader" />
    <div class="service-book-area" style="padding-top:0;" v-else >
      <!--Service list area-->
      <div id="services" class="service-list-area" style="padding-top:0;" v-if="products.length > 1">
        <!-- Multiple service -->
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="mb-5">
                
                <h2 class="section-title mb-0">{{ $t('lang.our_products') }}</h2>
                <p class="subtitle">{{ $t('lang.products_subtitle') }}</p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4 col-md-4 wow slideInUp animated" data-wow-duration="1.00s" data-wow-delay="0s" style="visibility: visible; animation-duration: 1s; animation-delay: 0s; animation-name: slideInUp;" v-for="product in products">
              <div class="card">
                <div
                  v-if="!product.image"
                  class="service-img-container height-11-rem"
                  :style="{'background-image': 'url(' + publicPath+'/uploads/' + 'service/service.png' + ')'}"
                ></div>
                <div
                  v-else
                  class="service-img-container height-11-rem"
                  :style="{'background-image': 'url(' + publicPath+'/files/' + product.image + ')'}"
                ></div>
                <div class="card-body">
                  <h5 class="card-title text-truncate mb-4">{{ product.title }}</h5>
                  <div class="row">
                    <div class="col-12">
                      <div class="media mb-3">
                        <div class="media-body" style="width:100%;">{{product.description }}</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="media mb-3">
                        <i class="las la-dollar-sign la-2x align-self-center mr-2" />
                        <div class="media-body">
                          <p class="service-info-heading">{{$t('lang.price_per_product')}}:</p>
                          <p
                            class="service-info-value mb-0"
                          >{{ numberFormatSalonGeneralBooking(product.price) }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="media">
                        <i class="las la-box la-2x align-self-center mr-2" />
                        <div class="media-body">
                          <p class="service-info-heading">{{$t('lang.available_quantity')}}:</p>
                          <p class="service-info-value mb-0">{{ product.quantity }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer pt-0">
                  
                  <a
                    v-if="product.quantity"
                    href="#"
                     class="btn2 br-50"
                     
                    @click.prevent="ordrProduct(product)"
                  >{{$t('lang.order')}}</a>
                </div>
              </div>
            </div>
             </div>
        </div>
      </div>
    </div>

        <!--Payment modal-->
        <div class="modal modal-landing fade" id="product-payment-modal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <pre-loader v-if="!hidePreLoader"/>
                    <div v-else class="modal-body justify-content-center">
                        <h4 class="mb-4 text-center">
                            {{ $t('lang.hi') }} {{first_name}} {{last_name}}! <br>
                            <span class="text-payment-confirm">
                                {{ $t('lang.confirm_payment_message') }}
                            </span>
                        </h4>
                        <h1 class="m-3 text-center text-landing-color text-net-price">
                              {{ numberFormatSalonGeneralBooking(this.getPrice()) }}
                        </h1>

                        <div class="payments-button-wrapper">
                            <div class="row" v-if="!enablePaypal">
                                <div class="col-12">
                                    <div class="payment-methods-button-container"
                                         v-for="paymentMethod in paymentMethods">
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-if="paymentMethod.type=='stripe' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                            {{paymentMethod.title}}
                                        </a>
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-if="paymentMethod.type=='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1 && paymentMethod.option"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=true,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type,pay()">
                                            {{paymentMethod.title}}
                                        </a>
                                        <a href="#"
                                           class="btn payment-methods-btn"
                                           v-else-if="paymentMethod.type!='stripe' && paymentMethod.type!='paypal' && paymentMethod.available_to_client==1 && paymentMethod.enable==1"
                                           :class="{'selectedPaymentMethod': paymentMethodClicked==paymentMethod.title}"
                                           @click.prevent="paymentMethodClicked = paymentMethod.title,isPaymentMethodDefault=false,selectedMethodId=paymentMethod.id,paymentMethodType=paymentMethod.type">
                                            {{paymentMethod.title}}
                                        </a>
                                    </div>
                                    <div class="row justify-content-center" v-if="!checkPaymentMethod(paymentMethods)">
                                        <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <div class="user-info-button">
                                                <button v-if="isPaymentMethodDefault"
                                                        class="btn btn-primary app-color mobile-btn btn-block"
                                                        id="default"
                                                        type="submit"
                                                        :disabled="!paymentMethodClicked"
                                                        @click.prevent="">
                                                    {{ $t('lang.order') }}
                                                </button>
                                                <button v-else
                                                        class="btn btn-primary app-color mobile-btn btn-block"
                                                        type="submit"
                                                        :disabled="!paymentMethodClicked"
                                                        @click.prevent="order(0, selectedMethodId)">
                                                    {{ $t('lang.order') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="enablePaypal">
                                <div class="col-12">
                                    <div class="payment-methods-button-container">
                                        <div id="paypal-button-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


 

    <!--quantity modal-->
    <div class="modal fade" id="quantity-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content px-4 pb-4 pt-3 text-center">
          <div class="row mt-2">
            <div class="form-group col-md-6">
              <label for="productName">{{ $t('lang.productname') }}</label>
              <input
                v-validate="'required'"
                :data-vv-as="$t('lang.productname')"
                name="productName"
                id="productName"
                type="text"
                class="form-control"
                :disabled="!isEmpty(productName)"
                v-model="productName"
              />
            </div>
            <div class="form-group col-md-6">
              <label for="quantity">{{ $t('lang.quantity') }}</label>
              <select
                class="form-control"
                id="exampleFormControlSelect2"
                v-if="productquantity"
                v-model="selectedquantity"
              >
                <option v-for="n in getNumbers(productquantity+1)" :key="n" :value="n">{{n}}</option>
              </select>
            </div>
            <hr class="solid" />

            <div class="form-group col-md-6">
              <label for="full_name">{{ $t('lang.full_name') }}</label>
              <input
                v-validate="'required'"
                :data-vv-as="$t('lang.full_name')"
                name="full_name"
                id="full_name"
                type="text"
                class="form-control"
                :disabled="!isEmpty(user)"
                v-model="full_name"
              />
            </div>

            <div class="form-group col-md-6">
              <label for="email">{{ $t('lang.input_email') }}</label>
              <input
                v-validate="'required'"
                :data-vv-as="$t('lang.input_email')"
                name="email"
                id="email"
                type="text"
                class="form-control"
                v-model="email"
                :disabled="!isEmpty(user)"
              />
            </div>
            <div class="form-group col-md-6">
              <label for="phone_number">{{ $t('lang.phone_number') }}</label>
              <vue-tel-input
                id="phone_number"
                type="text"
                v-model="phone_number"
                mode="international"
                name="phone_number"
                @input="getCountry"
                :disabled="!isEmpty(user)"
                @change="resetCustomEmailPhoneValidation"
                :placeholder="$t('lang.enter_phone')"
                class="form-control"
              ></vue-tel-input>
            </div>

            <div class="form-group col-md-6" v-if="productquantity">
              <label for="phone_number">{{ $t('lang.net_price') }}</label>
              <div class="alert price-label mb-0" role="alert">
                <span>{{ $t('lang.net_price') }}</span>
                <span class="float-right">{{ numberFormatSalonGeneralBooking(getPrice())}}</span>
              </div>
            </div>
          </div>

          <div class="my-4">
            <button
              v-if="productquantity"
              class="btn btn-landing"
              type="submit" 
             @click.prevent="checkConfirm">{{$t('lang.order')}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import "popper.js";
import axiosGetPost from "../../helper/axiosGetPostCommon";
import { VueTelInput } from "vue-tel-input";
export default {
  extends: axiosGetPost,
  props: ["payment_methods", "user", "checksignup", "service"],
  components: {
    VueTelInput,
  },
  data() {
    return {
      modalID: "#success-modal",
      hidePreLoader: true,
      hideCalenderLoader: true,
      continuePreLoader: false,
      first_name: "",
      last_name: "",
      full_name: "",
      email: "",
      phone_number: "",
      products: [],
      productName: "",
      productID: "",
      productPrice: "",
      productquantity: "",
      selectedquantity: "1",

      // Payment Variables
      selectedMethodId: 0,
      enablePaypal: false,
      paymentMethodType: "",
      paymentMethodClicked: false,
      isPaymentMethodDefault: false,
      slot: [],
      paymentMethods: [],
      paypalError: true,
      singleService: {},
      isEmailOrPhoneProvided: false,
      phone_object: {},
      cookieData: null,
      vid: "",
      isResetDateTimeClick: false,
    };
  },
  created() {
    //this.getServiceData('/getAllServiceFormData');
    var url = window.location.pathname;
    this.vid = url.substring(url.lastIndexOf("/") + 1);
    this.getProductsData("/getAllProductsFormDataByVendor/" + this.vid);
    this.languageCacheClearForCalender();
  },
  mounted() {
    let instance = this;

    if (instance.user) {
      instance.first_name = instance.user.first_name;
      instance.last_name = instance.user.last_name;
      instance.full_name =
        instance.user.first_name + " " + instance.user.last_name;

      instance.email = instance.user.email;
      instance.phone_number = instance.user.phone;
    }
    instance.modalCloseAction(instance.modalID);

    $("#success-modal").on("hidden.bs.modal", function (e) {
      instance.resetBook();
    });

    $("#product-payment-modal").on("hidden.bs.modal", function (e) {
      instance.selectedMethodId = 0;
      instance.enablePaypal = false;
      instance.paymentMethodClicked = false;
      $(".paypal-buttons").css("display", "none");
    });

    $(window).resize(function () {
      setTimeout(function () {
        instance.setDropdownWidth();
        instance.setTimeSlotPadding();
        instance.setDropdownGuestWidth();
        instance.setServiceImageWrapperHeight();
      });
    });
  },
  watch: {
    payment_methods: function (value) {
      this.paymentMethods = value;
      if (value) {
        this.checkPaymentTypeStripe();
      }
    },
  },
  methods: {
    isEmpty(obj) {
      return _.isEmpty(obj) ? true : false;
    },
    getCountry(number, isValid, country) {
      this.phoneObject = isValid.number.significant;
      this.setServiceIdCookie();
    },
    getPrice() {
      let instance = this;
      return instance.productPrice * instance.selectedquantity;
    },
    checkPaymentTypeStripe() {
      let instance = this;
      this.paymentMethods.forEach(function (paymentMethod) {
        if (paymentMethod.type == "stripe") {
          if (
            paymentMethod.available_to_client == 1 &&
            paymentMethod.enable == 1
          ) {
            instance.paymentForm();
          }
        }
      });
    },
    paymentForm() {
      let instance = this;
      let publishable_key = "";
      instance.paymentMethods.forEach(function (element) {
        if (element.type == "stripe") {
          publishable_key = element.option;
        }
      });
      if (publishable_key) {
        this.handler = StripeCheckout.configure({
          key: publishable_key,
          locale: "auto",
          image: instance.publicPath + "/images/stripe-logo.png",
          allowRememberMe: false,
          token: function (token) {
            // You can access the token ID with `token.id`.
            // Get the token ID to your server-side code for use.
            instance.hidePreLoader = false;
            instance.axiosPost(
              "/paymentstripe",
              {
                email: instance.email,
                token: token.id,
                bill: instance.getPrice(),
                method_id: instance.selectedMethodId,
              },
              function (response) {
                console.log(response);
                instance.order(instance.getPrice(), instance.selectedMethodId);
              },
              function (error) {
                instance.hidePreLoader = true;
              }
            );
          },
        });
      }
    },
    getNumbers: function (stop) {
      if (stop == 0) return new Array(0);
      var start = 1;
      return new Array(stop - start).fill(start).map((n, i) => n + i);
    },
    reSetBookingForm() {
      if (this.user == null) {
        this.isBookForm = false;
        this.first_name = "";
        this.last_name = "";
        this.full_name = "";
        this.email = "";
        this.phone_number = "";
      }
    },

    getService() {
      let instance = this;
      if (instance.service_id) {
        instance.continuePreLoader = true;
        instance.hideCalenderLoader = false;
        instance.axiosGet(
          "/service-by-id/" + instance.service_id,
          function (response) {
            instance.hideCalenderLoader = true;
            instance.isFullCalendar = true;
            instance.selectService = response.data;
            instance.selectTime = [];
            instance.serviceTiming = [];
            instance.availableSeat = instance.selectService.available_seat - 1;
            instance.netPrice = instance.selectService.price;
            if (instance.selectService.consider_children_for_price != "Yes") {
              instance.noOfChildren = 0;
              instance.noOfAdult = 1;
              instance.child = "Child";
            }
            instance.noOfAdult = 1;
            instance.ageRange = instance.selectService.age_range;
            instance.discount = instance.selectService.percentage;
            let service_employee = response.data.service_employee;

            if (!_.isEmpty(response.data.service_employee)) {
              response.data.service_employee.forEach((s_employee) => {
                instance.employees.push(s_employee.user);
              });
            } else {
              instance.employees = [];
            }
            instance.continuePreLoader = false;

            setTimeout(function () {
              instance.enableDisableDates();
              instance.controlPreAndNextBtn();
            });
            instance.setServiceIdCookie();
          },
          function (error) {
            instance.continuePreLoader = false;
          }
        );
      }
    },

    bookingForm() {
      let instance = this;
      this.isBookForm = true;
      this.isTime = false;
      this.isContinue = false;
      this.isCalendarSet = false;
      setTimeout(function () {
        $(".tooltip-change-date").tooltip({
          title: instance.$t("lang.click_here_to_change_date"),
          placement: "right",
          trigger: "hover",
        });
      }, 100);
      this.countNetPrice();
      this.setServiceIdCookie();
    },
    ordrProduct(product) {
      let instance = this;
      if (
        instance.checksignup.submit_booking_after_login === 1 &&
        instance.user == null
      ) {
        $("#authentication-warning-modal").modal("show");
      } else {
        instance.hidePreLoader = false;
        instance.axiosGet(
          "/getproduct/" + product.id,
          function (response) {
            console.log(response);
            instance.productName = response.data.title;
            instance.productID = response.data.id;
            instance.productPrice = response.data.price;
            instance.productquantity = response.data.quantity;

            instance.hidePreLoader = true;
            $("#quantity-modal").modal("show");
          },
          function (error) {
            instance.hidePreLoader = true;
          }
        );
      }
    },
    getProductsData(route) {
      let instance = this;
      instance.hidePreLoader = false;
      instance.axiosGet(
        route,
        function (response) {
          console.log(response);
          instance.products = response.data.products;
          instance.hidePreLoader = true;
        },
        function (error) {
          instance.hidePreLoader = true;
        }
      );
    },
    signIn() {
      this.redirect("/login");
    },
    register() {
      this.redirect("/register");
    },
    splitFullName(fullName) {
      let instance = this;
      if (fullName.indexOf(" ") >= 0) {
        let tempName = fullName.split(" ");

        instance.first_name = "";

        tempName.forEach((element, index) => {
          if (index != tempName.length - 1) {
            instance.first_name = instance.first_name + " " + element;
          }
        });

        instance.last_name = tempName[tempName.length - 1];
      } else {
        instance.first_name = fullName;
        instance.last_name = "";
      }
    },

    resetCustomEmailPhoneValidation() {
      let instance = this;
      instance.isEmailOrPhoneProvided = false;
    },
    save() {
      let instance = this;
      if (instance.noOfAdult == 0 && instance.noOfChildren == 0) {
        instance.seat = 1;
      } else {
        instance.seat = instance.noOfAdult + instance.noOfChildren;
      }
      instance.$validator.validateAll().then((result) => {
        instance.eitherEmailOrPhoneValidation();
        if (result && instance.eitherEmailOrPhoneValidation()) {
          //split full name into first name and last name
          instance.splitFullName(instance.full_name);

          instance.inputFields = {
            first_name: instance.first_name,
            last_name: instance.last_name,
            email: instance.email,
            phone_number: instance.phone_number,
            phone_object: instance.phoneObject,
            booking_date: instance.startDay,
            title: instance.selectService.title,
            price: instance.selectService.price,
            bill: instance.selectService.price,
            slot: instance.selectTime,
            seat: instance.seat,
            adult: instance.noOfAdult,
            children: instance.noOfChildren,
            id: instance.service_id,
            service_list: instance.service_id,
            duration_type: instance.selectService.service_duration_type,
            customFields: JSON.stringify(instance.customFieldsOptions),
          };
          instance.submitPreLoader = false;
          instance.axiosGETorPOST(
            {
              url: "/bookservice",
              postData: instance.inputFields,
            },
            (success, responseData) => {
              instance.submitPreLoader = true;
              instance.isBookForm = false;
              instance.isContinue = false;
              instance.isCalendar = false;
              instance.isTime = false;
              instance.isCalendarSet = false;
              instance.bookCalendar = false;
              instance.continuePreLoader = false;
              instance.calendar_inner = true;
              (instance.newCustomFieldData = {}),
                $("#success-modal").modal("show");
            },
            (error) => {
              instance.errors = error.data.errors;
              instance.submitPreLoader = true;
            }
          );
        }
      });
    },
    checkConfirm() {
      let instance = this;
       alert();
      if (!instance.checkPaymentMethod(instance.paymentMethods)) {
        instance.$validator.validateAll().then((result) => {
          if (result) {
            $('#quantity-modal').modal('hide');
            $("#product-payment-modal").modal("show");
          }
        });
      } else {
        instance.save();
      }
    },

    checkPaymentMethod(paymentMethods) {
      if (
        _.differenceWith(
          paymentMethods,
          [
            {
              available_to_client: 1,
              enable: 1,
            },
          ],
          _.isEqual
        ).length == 0
      ) {
        return true;
      } else {
        return false;
      }
    },
    
    pay() {
      let instance = this;
      this.$nextTick(function () {
        document
          .getElementById("default")
          .addEventListener("click", function (e) {
            if (instance.paymentMethodType == "paypal") {
              instance.enablePaypal = true;
              instance.paypal();
            } else {
              instance.handler.open({
                name: instance.appName,
                description:
                  "Bill: " + instance.currencyCode + " " + instance.netPrice,
                email: instance.email,
                closed: function () {},
              });
            }
            e.preventDefault();
          });
      });
    },
    order(bill, method, transaction_id = null) {
      let instance = this;
      if (instance.noOfAdult == 0 && instance.noOfChildren == 0) {
        instance.seat = 1;
      } else {
        instance.seat = instance.noOfAdult + instance.noOfChildren;
      }
      instance.$validator.validateAll().then((result) => {
        if (result) {
          //split full name into first name and last name
          instance.splitFullName(instance.full_name);

            instance.inputFields = {
            id: instance.productID,
            quantity:instance.selectedquantity,
          };
          instance.submitPreLoader = false;
          instance.hidePreLoader = false;
          instance.axiosGETorPOST(
            {
              url: "/orderPrduct",
              postData: instance.inputFields,
            },
            (success, responseData) => {
              instance.submitPreLoader = true;
              instance.hidePreLoader = true;
              instance.continuePreLoader = false;
              instance.calendar_inner = true;
              instance.newCustomFieldData = {};
              $("#product-payment-modal").modal("hide");
              $("#success-modal").modal("show");
            },
            (error) => {
              instance.errors = error.data.errors;
              instance.submitPreLoader = true;
              instance.hidePreLoader = true;
            }
          );
        }
      });
    },
  },
};
</script>
