/*=========================================================================================
  File Name: moduleCalendarActions.js
  Description: Calendar Module Actions
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

import axios from '@/axios.js'

export default {
    // addItem({ commit }, item) {
    //   return new Promise((resolve, reject) => {
    //     axios.post("/api/data-list/products/", {item: item})
    //       .then((response) => {
    //         commit('ADD_ITEM', Object.assign(item, {id: response.data.id}))
    //         resolve(response)
    //       })
    //       .catch((error) => { reject(error) })
    //   })
    // },
    fetchUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'teacher',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-users/' + school_id, requestOptions)
                .then((response) => {
                  //  console.log(response.data.users);
                    commit('SET_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

     fetchClassCode({ commit }) {
        var x = localStorage.getItem('accessToken');
        var user_id = localStorage.getItem('user_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'teacher',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-classes/' + user_id, requestOptions)
                .then((response) => {
                   // console.log(response.data.classes);
                    commit('SET_CLASSES', response.data.classes)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

    fetchStudents({ commit }) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'student',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-users/' + school_id, requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

    fetchParents({ commit }) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'parents',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-users/' + school_id, requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchUser(context, userId) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/fetch-user/${userId}`)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    UpdateUser({ commit }, data) {
        console.log(data);
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/update-profile/`, data)
                .then((response) => {


                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    removeRecord({ commit }, userId) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/delete-user/${userId}`)
                .then((response) => {
                    commit('REMOVE_RECORD', userId)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    //admin functions 
    fetchAdminUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            'type': 'teacher',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-admin-users/', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

     fetchSchoolAdmins({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            'type': 'school_admins',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-admin-users/', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_SCHOOL_ADMINS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchAdminStudents({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            'type': 'student',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-admin-users/', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchAdminParents({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            'type': 'parent',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-admin-users/', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
}