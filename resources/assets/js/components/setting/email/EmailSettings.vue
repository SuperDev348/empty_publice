<template>
    <div class="main-layout-card">
        <div class="main-layout-card-header">
            <div class="main-layout-card-content-wrapper">
                <div class="main-layout-card-header-contents">
                    <h5 class="bluish-text m-0">{{ $t('lang.email_settings') }}</h5>
                </div>
            </div>
        </div>
        <pre-loader v-if="!hidePreLoader"/>
        <div class="main-layout-card-content" v-else>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="application-name">{{ $t('lang.application_name') }}</label>
                        <input type="text" v-validate="'required'" name="name" class="form-control"
                               id="application-name" v-model="applicationName"
                               :class="{ 'is-invalid': submitted && errors.has('name') }">
                        <div class="heightError" v-if="submitted && errors.has('name')">
                            <small class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</small>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">{{ $t('lang.login_email') }}</label>
                        <input id="email" v-model="email" v-validate="'required|email'" name="email" type="email"
                               class="form-control" :class="{ 'is-invalid': submitted && errors.has('email') }">
                        <div class="heightError" v-if="submitted && errors.has('email')">
                            <small class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</small>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email-driver">{{ $t('lang.email_driver') }}</label>
                        <select id="email-driver" v-validate="'required'" name="driver" class="custom-select"
                                v-model="emailDriver" @change="setValidation()">
                            <option value="" disabled>{{ $t('lang.choose_one') }}</option>
                            <option value="smtp"> {{ $t('lang.smtp') }}</option>
                            <option value="sendmail"> {{ $t('lang.sendmail') }}</option>
                            <option value="mailgun"> {{ $t('lang.mailgun') }}</option>
                            <option value="mandrill"> {{ $t('lang.mandrill') }}</option>
                            <option value="sparkpost"> {{ $t('lang.sparkpost') }}</option>
                        </select>
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('driver')">{{ errors.first('driver') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-md-6" v-show="emailDriver === 'smtp'">
                        <label for="email-host">{{ $t('lang.host') }}</label>
                        <input id="email-host" v-validate="{required:isSmtpActive}" name="host" v-model="emailHost"
                               type="text" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('host')">{{ errors.first('host') }}</small>
                        </div>
                    </div>

                    <div class="form-group col-md-6" v-show="emailDriver === 'smtp'">
                        <label for="email-port">{{ $t('lang.port') }}</label>
                        <input id="email-port" v-validate="{required:isSmtpActive}" name="port" v-model="emailPort"
                               type="number" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('port')">{{ errors.first('port') }}</small>
                        </div>
                    </div>
                    <div class="form-group col-md-6" v-show="emailDriver === 'smtp'">
                        <label for="password">{{ $t('lang.password_email_settings') + email }}</label>
                        <input id="password" v-validate="{required:isSmtpActive}" name="password" v-model="password"
                               type="password" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('password')">
                                {{ errors.first('password') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group col-md-6" v-show="emailDriver === 'smtp'">
                        <label for="encryption-type">{{ $t('lang.encryption_type') }}</label>
                        <select id="encryption-type" v-validate="{required:isSmtpActive}" name="encryption"
                                class="custom-select" v-model="emailEncryptionType">
                            <option value="" disabled>{{ $t('lang.choose_one') }}</option>
                            <option value="tls">{{ $t('lang.tls') }}</option>
                            <option value="ssh">{{ $t('lang.ssh') }}</option>
                        </select>
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('encryption')">{{ errors.first('encryption')
                                }}
                            </small>
                        </div>
                    </div>

                    <!--mailgun -->
                    <div class="form-group col-md-6" v-show="emailDriver === 'mailgun'">
                        <label for="mailgun-domain">{{ $t('lang.mailgun_domain') }}</label>
                        <input id="mailgun-domain" v-validate="{required:isMailgunActive}" name="domain"
                               v-model="mailgunDomain" data-vv-as="mailgun domain" type="text" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('domain')">{{ errors.first('domain')}}</small>
                        </div>
                    </div>
                    <div class="form-group col-md-6" v-show="emailDriver === 'mailgun'">
                        <label for="mailgun-api">{{ $t('lang.mailgun_api') }}</label>
                        <input id="mailgun-api" v-model="mailgunApi" v-validate="{required:isMailgunActive}"
                               name="mailgunApi" data-vv-as="mailgun API" type="text" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('mailgunApi')">{{
                                errors.first('mailgunApi')}}
                            </small>
                        </div>
                    </div>

                    <!--test mail-->
                    <div class="form-group col-md-6">
                        <label for="test-mail">{{ $t('lang.test_mail') }}</label>
                        <input id="test-mail" v-model="testMail" type="text" class="form-control">
                    </div>

                    <!--mandril-->
                    <div class="form-group col-md-6" v-show="emailDriver === 'mandrill'">
                        <label for="mandrill-api">{{ $t('lang.mandrill_api') }}</label>
                        <input id="mandrill-api" v-model="mandrill" v-validate="{required:isMandrilActive}"
                               name="mandrill" data-vv-as="mandrill API" type="text" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('mandrill')">{{
                                errors.first('mandrill')}}
                            </small>
                        </div>
                    </div>

                    <!--sparkpost-->
                    <div class="form-group col-md-6" v-show="emailDriver === 'sparkpost'">
                        <label for="sparkpost-api">{{ $t('lang.sparkpost_api') }}</label>
                        <input id="sparkpost-api" v-model="sparkpost" v-validate="{required:isSparkpostActive}"
                               name="sparkpost" data-vv-as="sparkpost API" type="text" class="form-control">
                        <div class="heightError">
                            <small class="text-danger" v-show="errors.has('sparkpost')">{{
                                errors.first('sparkpost')}}
                            </small>
                        </div>
                    </div>

                </div>
                <button type="submit"
                        class="btn btn-primary app-color mobile-btn"
                        @click.prevent="updateEmailSetting()">
                    {{ $t('lang.save') }}
                </button>
            </form>
        </div>
    </div>
</template>


<script>
    import axiosGetPost from '../../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        data() {
            return {
                applicationName: '',
                email: '',
                emailDriver: '',
                emailHost: '',
                emailPort: '',
                password: '',
                emailEncryptionType: '',
                testMail: '',
                mailgunDomain: '',
                mailgunApi: '',
                mandrill: '',
                sparkpost: '',
                changeType: false,
                isSubmitted: false,
                isSmtpActive: false,
                isMailgunActive: false,
                isMandrilActive: false,
                isSparkpostActive: false,
                submitted: false,
                hidePreLoader: true
            }
        },
        created() {
            this.getEmailData('/emailsettingdata');
            this.setValidation();
        },
        methods: {
            setValidation() {
                this.$validator.reset();
                this.isSmtpActive = false;
                this.isMailgunActive = false;
                this.isMandrilActive = false;
                this.isSparkpostActive = false;

                if (this.emailDriver === 'smtp') {
                    this.isSmtpActive = true;
                } else if (this.emailDriver === 'mailgun') {
                    this.isMailgunActive = true;
                } else if (this.emailDriver === 'mandrill') {
                    this.isMandrilActive = true;
                } else if (this.emailDriver === 'sparkpost') {
                    this.isSparkpostActive = true;
                }
            },
            updateEmailSetting() {
                let instance = this;
                this.submitted = true;
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        instance.inputFields = {
                            email_from_name: this.applicationName,
                            email_from_address: this.email,
                            email_driver: this.emailDriver,
                            email_smtp_host: this.emailHost,
                            email_port: this.emailPort,
                            email_smtp_password: this.password,
                            email_encryption_type: this.emailEncryptionType,
                            test_mail: this.testMail,
                            mailgun_domain: this.mailgunDomain,
                            mailgun_api: this.mailgunApi,
                            mandrill: this.mandrill,
                            sparkpost: this.sparkpost,
                        };
                        instance.hidePreLoader = false;
                        instance.axiosPost('/emailsetting', instance.inputFields,
                            function (response) {
                                instance.hidePreLoader = true;
                            },
                            function (error) {
                                instance.errors = error.data.errors;
                                instance.hidePreLoader = true;
                            }
                        );
                    }
                });
            },
            getEmailData(route) {
                let instance = this;
                instance.hidePreLoader = false;
                instance.axiosGet(route,
                    function (response) {
                        instance.applicationName = response.data.email_from_name;
                        instance.email = response.data.email_from_address;
                        instance.emailDriver = response.data.email_driver;
                        instance.emailHost = response.data.email_smtp_host;
                        instance.emailPort = response.data.email_port;
                        instance.password = response.data.email_smtp_password;
                        instance.emailEncryptionType = response.data.email_encryption_type;
                        instance.testMail = response.data.test_mail;
                        instance.mailgunDomain = response.data.mailgun_domain;
                        instance.mailgunApi = response.data.mailgun_api;
                        instance.mandrill = response.data.mandrill_api;
                        instance.sparkpost = response.data.sparkpost_api;
                        instance.setValidation();
                        instance.hidePreLoader = true;
                    },
                    function (response) {
                        instance.hidePreLoader = true;
                    },
                );
            },

        },
    }
</script>