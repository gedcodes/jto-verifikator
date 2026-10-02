import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/wim', params).then((result) => result?.data).catch((error) => error?.response),
);

const getPagination = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/wim/active/getpage', params).then((result) => result?.data).catch((error) => error?.response),
);

const getSelected = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/wim/active/getselected', params).then((result) => result?.data).catch((error) => error?.response),
);

const force = (arrayIds = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/wim/delete/force/${arrayIds.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getAll,
    getPagination,
    getSelected,
    force,
};
// const ENDPOINT = '/api/verifikasi/bptd';
// export default {
//     getAll(params = '') {
//         return Axios.get(`${ENDPOINT}`);
//     },
//     get(id) {
//         return Axios.get(`${ENDPOINT}/${id}`);
//     },
//     create(data) {
//         return Axios.post(ENDPOINT, data);
//     },
//     update(id, data) {
//         return Axios.patch(`${ENDPOINT}/${id}`, data);
//     },
//     delete(id) {
//         return Axios.delete(`${ENDPOINT}/${id}`);
//     }
// };
