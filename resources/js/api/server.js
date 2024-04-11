export async function makeRequest(url) {
    let response = await fetch(url);
    let data = await response.json();
    return data;
}


export async function makeRequestPost(url) {
    let options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    };
    let response = await fetch(url, options);
    let data = await response.json();
    return data;
}
export async function makeRequestPostJson(url, json) {
    let options = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(json)
    };
    let response = await fetch(url, options);
    let data = await response.json();
    return data;
}
