<template>
    <div class="row  margin-fix">
        <div class="content-parent">
            <div class="content-child">
                <div class="col s12 m6 l4 offset-m3 offset-l4 center-align">
                    <div class="row">
                        <h5>{{ $t('lang.you_cant_book') }}</h5>
                        <h6>{{ $t('lang.please_login_and_try_again') }}</h6>
                    </div>
                    <div class="row">
                        <a href="#" @click="signIn" class="btn waves-effect waves-light bluish-back mob-btn">{{
                            $t('lang.sign_in') }}</a>
                        <a href="#" @click="register"
                           class="btn waves-effect cancel-btn waves-light bluish-back mob-btn"
                           v-if="checksignup.can_signup === 1">{{ $t('lang.sign_up') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';

    export default {
        extends: axiosGetPost,
        props: ['checksignup'],
        methods: {
            signIn() {
                let instance = this;
                instance.redirect('/login');
            },
            register() {
                let instance = this;
                instance.redirect('/register');
            }
        }
    }
</script>