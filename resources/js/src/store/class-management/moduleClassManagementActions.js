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

     fetchSchoolSubjects({ commit },payload) {
        var x = localStorage.getItem('accessToken');
        var school_id = localStorage.getItem('school_id');
        //  User Reward Card
        const requestOptions = {
           type: 'teacher',
            class_id: payload.class_id,
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/list_of_subjects/' + school_id, requestOptions)
                .then((response) => {
                    //console.log(response.data.classes);
                    commit('SET_SCHOOL_SUBJECTS', response.data.subjects)
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

      AddClassSubjects({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/add-subject`, code)
                .then((response) => {
                    if (response.data.data) {
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

    RemoveClassSubjects({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/remove-subject`, code)
                .then((response) => {
                    if (response.data.data) {
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
                    if (response.data.class) {
                        resolve(response)
                    } else {
                        reject(response.data.message)
                    }
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchSubjects({ commit }, classId) {
        var x = localStorage.getItem('accessToken');
        var user_id = localStorage.getItem('user_id');
        //  User Reward Card
        const requestOptions = {
            'type': 'teacher',
            headers: { 'Authorization': 'Bearer ' + x },

        };
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-subjects/' + classId)
                .then((response) => {
                    console.log(response.data.subjects);
                    commit('SET_SUBJECTS', response.data.subjects)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    saveSubject({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/save-subject`, code)
                .then((response) => {
                    if (response.data.subject) {
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
    removeSubject({ commit }, id) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/auth/delete-subject/${id}`)
                .then((response) => {
                    commit('REMOVE_SUBJECTS', id)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchSubjectDetail(context, id) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/fetch-subject-detail/${id}`)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    editSubject({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/edit-subject`, code)
                .then((response) => {
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