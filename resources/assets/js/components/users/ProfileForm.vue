<template>
    <form>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="first_name">{{ $t('lang.first_name') }}</label>
                <input id="first_name"
                       v-validate="'required|alpha_spaces'"
                       v-model="first_name"
                       type="text"
                       data-vv-as="first name"
                       name="first_name"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && errors.has('first_name')}">
                <div class="heightError" v-if="submitted && errors.has('first_name')">
                    <small class="text-danger" v-show="errors.has('first_name')">
                        {{ errors.first('first_name') }}
                    </small>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="last_name">{{ $t('lang.last_name') }}</label>
                <input id="last_name"
                       v-validate="'required|alpha_spaces'"
                       v-model="last_name"
                       type="text"
                       data-vv-as="last name"
                       name="last_name"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && errors.has('last_name') }">
                <div class="heightError" v-if="submitted && errors.has('last_name')">
                    <small class="text-danger" v-show="errors.has('last_name')">
                        {{ errors.first('last_name') }}
                    </small>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="email">{{ $t('lang.email_address') }}</label>
                <input id="email"
                       v-validate="'required'"
                       v-model="email"
                       type="email"
                       name="email"
                       class="form-control"
                       :class="{ 'is-invalid': submitted && errors.has('email') }">
                <div class="heightError" v-if="submitted && errors.has('email')">
                    <small class="text-danger" v-show="errors.has('email')">
                        {{ errors.first('email') }}
                    </small>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="email">{{ $t('lang.phone_number') }}</label>
                <input id="phone_number"
                       v-validate="'numeric'"
                       v-model="phone"
                       type="text"
                       name="phone"
                       class="form-control">
                <div class="heightError" v-if="submitted && errors.has('phone')">
                    <small class="text-danger" v-show="errors.has('phone')">
                        {{ errors.first('phone') }}
                    </small>
                </div>
            </div>

            <div class="form-group col-md-6 profileBirthDate">
                <label for="birth-date">{{ $t('lang.date_of_birth') }}</label>
                <datepicker id="birth-date"
                            :disabledDates="state.disabledDates"
                            v-model="selectedDate"
                            :format="datePickerDateFormatter(selectedDate)"
                            :bootstrap-styling="true">
                </datepicker>
            </div>
            <div class="form-group col-md-6">
                <label for="gender">{{ $t('lang.gender') }}</label>
                <select v-model="profile.gender" id="gender" class="custom-select">
                    <option value="" disabled>{{ $t('lang.choose_one') }}</option>
                    <option value="Male"> {{ $t('lang.male') }}</option>
                    <option value="Female"> {{ $t('lang.female') }}</option>
                    <option value="Others"> {{ $t('lang.others') }}</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('lang.change_profile_image') }}</label>
                <div class="custom-file">
                    <input type="file"
                           class="custom-file-input"
                           id="profile-image-change"
                           accept="image/*"
                           @change="profileImageChange">
                    <label class="custom-file-label text-truncate" for="profile-image-change">
                        {{ $t('lang.image_only') }}
                    </label>
                </div>
            </div>
            <div class="col-12">
                <common-submit-button class="btn-profile-component"
                                      :buttonLoader="buttonLoader"
                                      :isDisabled="isDisabled"
                                      :isActiveText="isActiveText"
                                      buttonText="save"
                                      v-on:submit="updateProfile"/>
            </div>
        </div>
    </form>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        props: ['profile'],
        extends: axiosGetPost,
        components: {
            Datepicker
        },
        data() {
            return {
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                date_of_birth: null,
                gender: '',
                password: '',
                avatar: '',
                isActive: 1,
                maxDate: '',
                currentDate: '',
                state: {},
                selectedDate: this.profile.date_of_birth,
                birthDate: '',
                buttonLoader: false,
                isActiveText: false,
                isDisabled: false,
                submitted: false,
            }
        },
        mounted() {
            this.first_name = this.profile.first_name;
            this.last_name = this.profile.last_name;
            this.email = this.profile.email;
            this.phone = this.profile.phone;
            this.state = {
                disabledDates: {
                    to: new Date(1001, 0, 5),
                    from: new Date(),
                }
            };

            let instance = this;
            $('.datepicker').on('change', function () {
                let dob = $(this).val();
                instance.profile.date_of_birth = dob;
            });

            $('#gender').on('change', function () {
                let gender2 = $(this).val();
                instance.profile.gender = gender2
            });

            this.currentDate = moment().format('l');
            this.maxDate = moment(this.currentDate, 'MM/DD/YYYY').format('YYYY-MM-DD');
        },

        methods: {
            customFormatter(date) {
                return moment(date).format(this.dateFormat);
            },
            profileImageChange(event) {
                let input = event.target;

                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.profile.avatar = e.target.result;
                        this.avatar = e.target.result;
                    };

                    reader.readAsDataURL(input.files[0]);
                    let fileName = input.files[0].name;
                    $('#profile-image-change').next('.custom-file-label').html(fileName);
                }
                else {
                    this.profile.avatar = '';
                    $('#profile-image-change').next('.custom-file-label').html(this.$t('lang.image_only'));
                }
            },
            updateProfile() {

                this.submitted = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.inputFields = {
                            email: this.email,
                            first_name: this.first_name,
                            last_name: this.last_name,
                        };
                        this.buttonLoader = true;
                        this.isActiveText = true;
                        this.isDisabled = true;
                        this.postProfileData('/profile/' + this.profile.id, {
                            first_name: this.first_name,
                            last_name: this.last_name,
                            email: this.email,
                            phone: this.phone,
                            date_of_birth: this.selectedDate,
                            gender: this.profile.gender,
                            password: this.profile.password,
                            avatar: this.profile.avatar,
                        });
                    }
                });
            },
            postProfileData(route, fields) {
                let instance = this;
                instance.axiosPost(route, fields,
                    function (response) {
                        window.location.reload();
                        instance.showSuccessAlert(response.message);
                    },
                    function (response) {
                        let instance = this;
                        instance.buttonLoader = false;
                        instance.isActiveText = false;
                        instance.isDisabled = false;
                    }
                );
            },
        },
    }
</script>