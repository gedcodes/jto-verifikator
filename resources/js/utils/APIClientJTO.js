import axios from 'axios';
import appConfig from '../../../appconfig.json';

let obj = { UrlServerJTO: 'http://127.0.0.1' };

if (appConfig) {
  obj = appConfig;
}

const Client = axios.create({
    // baseURL: Config.API_URL,
    baseURL: obj.UrlServerJTO,//localStorage.getItem('urlJTO'),//'http://192.168.5.111:8021/api/v2pv', // Config.API_URL source from appconfig.json,
    headers: {
        'Content-Type': 'application/json',
    },
    // timeout: 5000,
});

const getIntance = (url, params, config) => Client.get(url, {
    params,
    ...config,
});

const postIntance = (url, data, config) => Client.post(url, data, {
    ...config,
});

const putIntance = (url, data, config) => Client.put(url, data, {
    ...config,
});

const patchIntance = (url, data, config) => Client.patch(url, data, {
    ...config,
});

const deleteIntance = (url, config) => Client.delete(url, {
    ...config,
});

const withAuth = async () => {
    // const { token } = localStorage.getItem('token'); // await getToken();
    const setConfig = (config) => ({
        ...config,
        headers: {
            ...config?.headers,
            Authorization: localStorage.getItem('tokenJTO'),//'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MywiaWF0IjoxNjU1ODAyODEwLCJleHAiOjE2ODczMzg4MTB9.6JjcJlMHH5pd4oeO7-6hwSPtRdYlifXW-0-xaw2u5_U',//localStorage.getItem('token'),
        },
    });

    return {
        get: (url, params, config) => getIntance(url, params, setConfig(config)),
        post: (url, data, config) => postIntance(url, data, setConfig(config)),
        put: (url, data, config) => putIntance(url, data, setConfig(config)),
        patch: (url, data, config) => patchIntance(url, data, setConfig(config)),
        delete: (url, config) => deleteIntance(url, setConfig(config)),
    };
};

export default {
    get: getIntance,
    post: postIntance,
    put: putIntance,
    patch: patchIntance,
    delete: deleteIntance,
    withAuth,
};
