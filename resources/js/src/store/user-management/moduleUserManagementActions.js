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
    fetchSearch({ commit }) {
        // console.log('user_id',localStorage.getItem('user_id'));
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        var from_user = localStorage.getItem('user_id');

        //  User Reward Card
        const requestOptions = {
            'type': 'searchdata',
            'from_user': from_user,
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
    fetchSeacrhRecord({commit}){

        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
          'subject_id': a.value,
          'class_id': this.isclassFilter.id,
          'school_id': school_id,
          headers: { 'Authorization': 'Bearer ' + x },
  
        };
        return new Promise((resolve, reject) => {
            axios.post("/api/auth/get_record", requestOptions)
          .then(response => {
            // this.usersDatas(response.data.users);
            commit('SET_USERS', response.data.users)
            resolve(response)
          })
          .catch(error => {
            console.log(error);
          });
        })
       
    },
    
    fulltimeUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'fulltime-teacher',
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
    contractualUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'contractual-teacher',
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
            axios.post(`/api/auth/update-profile`, data)
                .then((response) => {


                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

    SaveStudent({ commit }, data) {
        console.log(data);
        return new Promise((resolve, reject) => {
            axios.post(`/api/v1/signup_student`, data)
                .then((response) => {


                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },


    SaveTeacher({ commit }, data) {
        console.log(data);
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/signup_teacher`, data)
                .then((response) => {


                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },

    SaveParent({ commit }, data) {
        console.log(data);
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/signup_parent`, data)
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
                    commit('REMOVE_RECORD_ADMIN', userId)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    // Place a Request fucntion 
    placeRecord({ commit }, userId) {
      
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/place-user/${userId}`)
                .then((response) => { 
                    window.console.log(response)
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
            axios.post('/api/auth/manage-admin-users', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchAdminProgramUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            'type': 'program-teacher',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-admin-users', requestOptions)
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
            axios.post('/api/auth/manage-admin-users', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
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
            axios.post('/api/auth/manage-admin-users', requestOptions)
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
            axios.post('/api/auth/manage-admin-users', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_ADMIN_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchReportUsers({ commit }) {
        var x = localStorage.getItem('accessToken');
        //  User Reward Card
        const requestOptions = {
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-report-users', requestOptions)
                .then((response) => {
                    console.log(response.data.users);
                    commit('SET_REPORT_USERS', response.data.users)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
}