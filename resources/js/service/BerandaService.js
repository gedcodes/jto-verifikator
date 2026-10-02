import ApiClient from '../utils/APIClient';
const getResume = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/resume', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPerBulan = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/getperbulan', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPerJam = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/getperjam', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPerJenisKendaraan = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/getperjeniskendaraan', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPerKategoriKepemilikan = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/getperkategorikepemilikan', params).then((result) => result?.data).catch((error) => error?.response),
);
const getPerBerat = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/beranda/getperberat', params).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getResume,
    getPerBulan,
    getPerJam,
    getPerJenisKendaraan,
    getPerKategoriKepemilikan,
    getPerBerat,
};
// const ENDPOINT = '/api/setup/bptd';
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
