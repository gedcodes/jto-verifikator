import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture', params).then((result) => result?.data).catch((error) => error?.response),
);

const sink = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/publish/sink', params).then((result) => result?.data).catch((error) => error?.response),
);

const sinkDeteksi = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/publish/sinkdeteksi', params).then((result) => result?.data).catch((error) => error?.response),
);

const getAllActive = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/active/publish', params).then((result) => result?.data).catch((error) => error?.response),
);

const getByDate = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/active/getbydate', params).then((result) => result?.data).catch((error) => error?.response),
);

const getPagination = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/active/getpage', params).then((result) => result?.data).catch((error) => error?.response),
);

const getSelected = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/capture/active/getselected', params).then((result) => result?.data).catch((error) => error?.response),
);

const getById = (id) => ApiClient.withAuth().then(
    (api) => api.get(`/verifikasi/capture/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const create = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/verifikasi/capture', payload).then((result) => result?.data).catch((error) => error?.response),
);

const edit = (id, payload) => ApiClient.withAuth().then(
    (api) => api.put(`/verifikasi/capture/${id}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const trash = (id) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/capture/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const editVerifArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/verifikasi/capture/active/edit/${arrayIds.join()}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const trashArr = (id = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/capture/trash/arr/${id.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

const editStatusArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/verifikasi/capture/active/update/${arrayIds.join()}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const force = (arrayIds = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/capture/delete/force/${arrayIds.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getAll,
    sink,
    sinkDeteksi,
    getAllActive,
    getById,
    getByDate,
    getPagination,
    getSelected,
    create,
    edit,
    trash,
    trashArr,
    editVerifArray,
    editStatusArray,
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
