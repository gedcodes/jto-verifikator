import ApiClient from '../utils/APIClient';
const getAll = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran', params).then((result) => result?.data).catch((error) => error?.response),
);

const getAllActive = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran/active/publish', params).then((result) => result?.data).catch((error) => error?.response),
);

const getByDate = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran/active/getbydate', params).then((result) => result?.data).catch((error) => error?.response),
);

const getById = (id) => ApiClient.withAuth().then(
    (api) => api.get(`/verifikasi/pelanggaran/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const exportOne = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran/active/exportpdf', params).then((result) => result?.data).catch((error) => error?.response),
);

const laporanpdf = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran/active/laporanpdf', params).then((result) => result?.data).catch((error) => error?.response),
);

const laporanexcel = (params = {}) => ApiClient.withAuth().then(
    (api) => api.get('/verifikasi/pelanggaran/active/laporanexcel', params).then((result) => result?.data).catch((error) => error?.response),
);

const create = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/verifikasi/pelanggaran', payload).then((result) => result?.data).catch((error) => error?.response),
);

const archive = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/verifikasi/pelanggaran/archive', payload).then((result) => result?.data).catch((error) => error?.response),
);

const archiveArr = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/verifikasi/pelanggaran/archivearr', payload).then((result) => result?.data).catch((error) => error?.response),
);

const createDetail = (payload) => ApiClient.withAuth().then(
    (api) => api.post('/verifikasi/pelanggaran/createdetail', payload).then((result) => result?.data).catch((error) => error?.response),
);

const edit = (id, payload) => ApiClient.withAuth().then(
    (api) => api.put(`/verifikasi/pelanggaran/${id}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const trash = (id) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/pelanggaran/${id}`).then((result) => result?.data).catch((error) => error?.response),
);

const trashArr = (id = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/pelanggaran/trash/arr/${id.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

const editVerifArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/verifikasi/pelanggaran/active/edit/${arrayIds.join()}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const editStatusArray = (arrayIds = [], payload) => ApiClient.withAuth().then(
    (api) => api.patch(`/verifikasi/pelanggaran/active/update/${arrayIds.join()}`, payload).then((result) => result?.data).catch((error) => error?.response),
);

const force = (arrayIds = []) => ApiClient.withAuth().then(
    (api) => api.delete(`/verifikasi/pelanggaran/delete/force/${arrayIds.join()}`).then((result) => result?.data).catch((error) => error?.response),
);

export default {
    getAll,
    getAllActive,
    getById,
    getByDate,
    exportOne,
    laporanpdf,
    laporanexcel,
    create,
    archive,
    archiveArr,
    createDetail,
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
