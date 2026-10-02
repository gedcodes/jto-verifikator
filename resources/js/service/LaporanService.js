import ApiClient from '../utils/APIClient';
const pelanggaran = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/pelanggaran', params).then((result) => result?.data).catch((error) => error?.response),
);

const perjeniskendaraan = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/perjeniskendaraan', params).then((result) => result?.data).catch((error) => error?.response),
);

const perjenispelanggaran = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/perjenispelanggaran', params).then((result) => result?.data).catch((error) => error?.response),
);

const perkategori = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/perkategori', params).then((result) => result?.data).catch((error) => error?.response),
);

const perberat = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/perberat', params).then((result) => result?.data).catch((error) => error?.response),
);

const all = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/all', params).then((result) => result?.data).catch((error) => error?.response),
);

const print = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/printpdf', params).then((result) => result?.data).catch((error) => error?.response),
);

const excel = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/laporan/exportexcel', params).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    pelanggaran,
    perjeniskendaraan,
    perjenispelanggaran,
    perkategori,
    perberat,
    all,
    print,
    excel,
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
