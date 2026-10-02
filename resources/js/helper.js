export function kapitalisasiKata(str) {
    return str.replace(/\w\S*/g, function (kata) {
        const kataBaru = kata.slice(0, 1).toUpperCase() + kata.substr(1);
        return kataBaru
    });
}
