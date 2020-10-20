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
    fetchSchoolSubjects({ commit }, schoolId) {
        return new Promise((resolve, reject) => {
            axios.post('/api/auth/manage-school-subjects/' + schoolId)
                .then((response) => {
                    console.log(response.data.subjects);
                    commit('SET_SCHOOL_SUBJECTS', response.data.subjects)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    saveSchoolSubject({ commit }, code) {
        return new Promise((resolve, reject) => {
            axios.post(`/api/auth/save-school-subject`, code)
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
    removeSchoolSubject({ commit }, id) {
        return new Promise((resolve, reject) => {
            axios.delete(`/api/auth/delete-subject/${id}`)
                .then((response) => {
                    commit('REMOVE_SCHOOL_SUBJECTS', id)
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    fetchSchoolSubjectDetail(context, id) {
        return new Promise((resolve, reject) => {
            axios.get(`/api/auth/fetch-subject-detail/${id}`)
                .then((response) => {
                    resolve(response)
                })
                .catch((error) => { reject(error) })
        })
    },
    editSchoolSubject({ commit }, code) {
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
}