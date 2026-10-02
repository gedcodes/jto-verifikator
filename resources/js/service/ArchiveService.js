import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/archive', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPaging = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('verifikasi/archive/active/publish', params).then((result) => result?.data).catch((error) => error?.response),
);
const getSelected = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('verifikasi/archive/active/selected', params).then((result) => result?.data).catch((error) => error?.response),
);


export default {
    getAll,
    getPaging,
    getSelected,
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
