/*=========================================================================================
  File Name: moduleAuthState.js
  Description: Auth Module State
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/



export default {
    isUserLoggedIn: () => {
        let isAuthenticated = false
            // get firebase current user


        let userInfo = localStorage.getItem('userInfo');
        // return  (userInfo && isAuthenticated)
        return userInfo
    },
    logout: () => {
        // remove user from local storage to log user out
        localStorage.removeItem('userInfo');
        localStorage.removeItem('user_id');
        localStorage.removeItem('school_id');
        localStorage.removeItem('accessToken');
        localStorage.removeItem('role_id');
        this.$router.push('/pages/login').catch(() => {})
    }
}