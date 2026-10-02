import ApiClient from '../utils/APIClient';
import ApiClientJTO from '../utils/APIClientJTO';
const getByNokend = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/kendaraan', params).then((result) => result?.data).catch((error) => error?.response),
);

const getUjiBerkala = (params = {}) => ApiClientJTO.withAuth().then(
    (api) => api.get('/v2pv/kendaraan/ujiberkala', params).then((result) => result?.data).catch((error) => error?.response),
);

const findPelanggaran = (payload) => ApiClientJTO.withAuth().then(
    (api) => api.post('/v2pv/penimbangan/findPelanggaran', payload).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getByNokend,
    getUjiBerkala,
    findPelanggaran,
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
