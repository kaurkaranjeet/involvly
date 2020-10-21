/*=========================================================================================
  File Name: moduleCalendarMutations.js
  Description: Calendar Module Mutations
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


export default {
    SET_CLASSES(state, classes) {
        state.classes = classes
    },
    REMOVE_RECORD(state, classId) {
        const classIndex = state.classes.findIndex((u) => u.id === classId)
        state.classes.splice(classIndex, 1)
    },
    SET_SUBJECTS(state, subjects) {
        state.subjects = subjects
    },
    REMOVE_SUBJECTS(state, subjectId) {
        const subjectIndex = state.subjects.findIndex((u) => u.id === subjectId)
        state.subjects.splice(subjectIndex, 1)
    },
    SET_SCHOOL_SUBJECTS(state, school_subjects) {
        state.school_subjects = school_subjects
    },
    SET_TEACHERS(state, teachers) {
        state.teachers = teachers
    },

}