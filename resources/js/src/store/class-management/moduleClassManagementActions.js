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
                    console.log(response.data.classes);
                    commit('SET_CLASSES', response.data.classes)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    saveClassCode({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/save-class-code`, code)
                .then((response) => {
                    if (response.data.class) {
                        resolve(response)
                    } else {
                        reject(response.data.message)
                    }
                })
                .catch((error) => {
                    reject(error)
                })

        })
    },
    removeClassCode({ commit }, id) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/auth/delete-class-code/${id}`)
                .then((response) => {
                    commit('REMOVE_RECORD', id)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchClassCodeDetail(context, id) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/fetch-class-detail/${id}`)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    editClassCode({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/edit-class-code`, code)
                .then((response) => {
                    console.log("reee", response)
                    if (response) {
                        resolve(response)
                    } else {
                        reject(response.data.message)
                    }
                })
                .catch((error) => { reject(error) })
        })
    },

    fetchAllItems({ commit }) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/all-items`)
                .then((response) => {
                    commit('SET_ITEMS', response.data.response)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchItems({ commit }, userId) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/view-items/${userId}`)
                .then((response) => {
                    commit('SET_ITEMS', response.data.response)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchItem(context, userId) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/item-user/${userId}`)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    getItem({ commit }, itemId) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/get-item/${itemId}`)
                .then((response) => {
                    commit('SET_ITEMS', response.data.response)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    saveItem(context, userId) {
        console.log(userId);
        return new Promise((resolve, reject) => {
            axios.post(`/api/update-item/${userId.id}`, userId)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    uploadImage(context, userId) {
        console.log(userId);
        return new Promise((resolve, reject) => {
            axios.post(`/api/update-image`, userId)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    createItem(context, userId) {
        console.log('userId');
        console.log(userId);
        return new Promise((resolve, reject) => {
            axios.post(`/api/create-item`, userId)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    removeRecord({ commit }, ItemId) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/delete-item/${ItemId}`)
                .then((response) => {
                    commit('REMOVE_RECORD', ItemId)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    }
}