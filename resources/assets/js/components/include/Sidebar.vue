<template>
    <div class="side-bar">
        <ul>
            <li class="side-bar-logo">
                <a href="#" @click="dashboardclick">
                    <img :src="publicPath+'/uploads/logo/'+appLogo" alt="">
                </a>
            </li>
            <li :class="{'active-side-bar': route == 'dashboard' }">
                <a href="#" @click="dashboardclick">
                    <i class="la la-desktop la-3x"></i>
                    <div>{{ $t('lang.dashboard') }}</div>
                </a>
            </li>
            <li v-if="workers==1" :class="{'active-side-bar': route == 'workers' }">
                <a href="#" @click="workersclick">
                    <i class="la la-users"></i>
                    <div>{{ $t('lang.workers') }}</div>
                </a>
            </li>            
            <li v-if="services==1" :class="{'active-side-bar': route == 'services' }">
                <a href="#" @click="servicesclick">
                    <i class="la la-globe"></i>
                    <div>{{ $t('lang.services') }}</div>
                </a>
            </li>
                 <li v-if="services==1" :class="{'active-side-bar': route == 'products' }">
                <a href="#" @click="productsclick">
                    <i class="la la-box"></i>
                    <div>{{ $t('lang.products') }}</div>
                </a>
            </li>
            <li v-if="booking==1" :class="{'active-side-bar': route == 'bookings' || routeurl.search('booking-details')>-1 }">
                <a href="#" @click="bookingsclick">
                    <i class="la la-credit-card"></i>
                   <div> {{ $t('lang.bookings') }}</div>
                </a>
            </li>
                  <li v-if="orders==1" :class="{'active-side-bar': route == 'orders' || routeurl.search('orders')>-1 }">
                <a href="#" @click="ordersclick">
                    <i class="la la-credit-card"></i>
                   <div> {{ $t('lang.orders') }}</div>
                </a>
            </li>
              <li v-if="vendormanage==1" :class="{'active-side-bar': route == 'vendors' || routeurl.search('vendors')>-1 }">
                <a href="#" @click="vendorsclick">
                    <i class="la la-suitcase"></i>
                   <div> {{ $t('lang.vendors') }}</div>
                </a>
            </li>
                  <li v-if="bannermanage==1" :class="{'active-side-bar': route == 'banners' || routeurl.search('banners')>-1 }">
                <a href="#" @click="bannersclick">
                    <i class="lab la-adversal"></i>
                   <div> banners</div>
                </a>
            </li>
            <li v-if="clientmanage==1" :class="{'active-side-bar': route == 'clients' || routeurl.search('client')>-1 }">
                <a href="#" @click="clientsclick">
                    <i class="la la-users"></i>
                    <div> {{ $t('lang.clients') }}</div>
                </a>
            </li>
            <li v-if="reportpermission == 1" :class="{'active-side-bar': route == 'reports' }">
                <a href="#" @click="reportsclick">
                    <i class="la la-pie-chart"></i>
                   <div> {{ $t('lang.reports') }}</div>
                </a>
            </li> 
            <li v-if="userroles==1 || usermanage==1 || appsettings==1 || emailsettings == 1 || offdaysettings == 1|| vendorProfile == 1 || holidaysettings == 1 || emailtemplate == 1 || clientsetting == 1" :class="{'active-side-bar': route == 'settings' || routeurl.search('/user')>-1}">
                <a href="#" @click="settingsclick">
                    <i class="la la-cogs la-3x"></i>
                    <div>{{ $t('lang.settings') }}</div>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
    import axiosGetPost from '../../helper/axiosGetPostCommon';
    export default {
        extends: axiosGetPost, 
        props:['route','routeurl','workers','services','booking','orders','userroles','usermanage','vendormanage','bannermanage','appsettings','emailsettings','offdaysettings','vendorProfile','holidaysettings','emailtemplate', 'clientmanage', 'reportpermission', 'clientsetting'],

        methods: {
            dashboardclick(){
                let instance=this;
                instance.redirect('/dashboard');
            },
            workersclick() {
                let instance = this;
                instance.redirect('/workers');
            },
            servicesclick(){ 

                let instance=this;
                instance.redirect('/services');
            },
            productsclick(){ 

                let instance=this;
                instance.redirect('/products');
            },
            bookingsclick(){
                let instance=this;
                instance.redirect('/bookings');
            },
                ordersclick(){
                let instance=this;
                instance.redirect('/orders');
            },
             vendorsclick(){
                let instance=this;
                instance.redirect('/vendors');
            },
             bannersclick(){
                let instance=this;
                instance.redirect('/banners');
            },
            clientsclick(){
                let instance=this;
                instance.redirect('/clients');
            },
            reportsclick(){
                let instance=this;
                instance.redirect('/reports');
            },
            settingsclick(){
                let instance=this;
                instance.redirect('/settings');
            },
        }
    }
</script>