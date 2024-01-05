const content = $('#content');
const skeleton = $('#skeleton');
const load_more_btn_parent = $('#load_more_btn_parent');
const load_more_btn = $('#load_more_btn');

const suggestions_count = $('#suggestions_count');
const connections_count = $('#connections_count');
const sent_requests_count = $('#sent_requests_count');
const received_requests_count = $('#received_requests_count');

function getSentRequests() {
    let pageUrl;

    load_more_btn.on('click', () => {
        if ($('input[name="active_tab"]:checked').val() === 'sent_requests') {
            getData(pageUrl);
        }
    });

    $('#get_sent_requests_btn').on('click', () => {
        content.html('');
        getData('/requests/sent');
    });

    function getData(url) {
        if (url === undefined) return;

        skeleton.removeClass('d-none');

        axios.get(url).then((rsp) => {
            const nextPage = rsp.data.links.next;

            sent_requests_count.text(rsp.data.meta.total);

            rsp.data.data.forEach((connectionRequest) => {
                content.append(`
                    <div class="my-2 shadow text-white bg-dark p-1 sent-request">
                      <div class="d-flex justify-content-between">
                        <table class="ms-1">
                          <td class="align-middle">${connectionRequest.receiver.name}</td>
                          <td class="align-middle"> - </td>
                          <td class="align-middle">${connectionRequest.receiver.email}</td>
                          <td class="align-middle">
                        </table>
                        <div>
                          <button class="btn btn-danger me-1 cancel_request_btn" data-id="${connectionRequest.id}">
                          Withdraw Request</button>
                        </div>
                      </div>
                    </div>
                `);
            })

            if (rsp.data.data.length === 0) {
                content.append(`
                    <span class="fw-bold">There are no records.</span>
                `);
            }

            skeleton.addClass('d-none');

            if (nextPage !== null) {
                load_more_btn_parent.removeClass('d-none');
                pageUrl = nextPage;
            } else {
                load_more_btn_parent.addClass('d-none');
            }
        }).catch((error => {
            console.log(error)
        }))
    }
}

function getReceivedRequests() {
    let pageUrl;

    load_more_btn.on('click', () => {
        if ($('input[name="active_tab"]:checked').val() === 'received_requests') {
            getData(pageUrl);
        }
    });

    $('#get_received_requests_btn').on('click', () => {
        content.html('');
        getData('/requests/received');
    });

    function getData(url) {
        if (url === undefined) return;

        skeleton.removeClass('d-none');

        axios.get(url).then((rsp) => {
            const nextPage = rsp.data.links.next;

            received_requests_count.text(rsp.data.meta.total);

            rsp.data.data.forEach((connectionRequest) => {
                content.append(`
                    <div class="my-2 shadow text-white bg-dark p-1 received-request">
                      <div class="d-flex justify-content-between">
                        <table class="ms-1">
                          <td class="align-middle">${connectionRequest.sender.name}</td>
                          <td class="align-middle"> - </td>
                          <td class="align-middle">${connectionRequest.sender.email}</td>
                          <td class="align-middle">
                        </table>
                        <div>
                          <button class="btn btn-primary me-1 accept_request_btn" data-id="${connectionRequest.id}">
                          Accept</button>
                        </div>
                      </div>
                    </div>
                `);
            })

            if (rsp.data.data.length === 0) {
                content.append(`
                    <span class="fw-bold">There are no records.</span>
                `);
            }

            skeleton.addClass('d-none');

            if (nextPage !== null) {
                load_more_btn_parent.removeClass('d-none');
                pageUrl = nextPage;
            } else {
                load_more_btn_parent.addClass('d-none');
            }
        }).catch((error => {
            console.log(error)
        }))
    }
}

function getConnections() {
    let pageUrl;

    load_more_btn.on('click', () => {
        if ($('input[name="active_tab"]:checked').val() === 'connections') {
            getData(pageUrl);
        }
    });

    $('#get_connections_btn').on('click', () => {
        content.html('');
        getData('/connections');
    });

    function getData(url) {
        if (url === undefined) return;

        skeleton.removeClass('d-none');

        axios.get(url).then((rsp) => {
            const nextPage = rsp.data.links.next;

            connections_count.text(rsp.data.meta.total);

            rsp.data.data.forEach((user) => {
                const is_disabled = user.common_connections_count > 0 ? '' : 'disabled';

                content.append(`
                        <div class="my-2 shadow text-white bg-dark p-1 connection">
                          <div class="d-flex justify-content-between">
                            <table class="ms-1">
                              <td class="align-middle">${user.name}</td>
                              <td class="align-middle"> - </td>
                              <td class="align-middle">${user.email}</td>
                              <td class="align-middle">
                            </table>
                            <div>
                              <button style="width: 220px" class="btn btn-primary ${is_disabled} get_connections_in_common" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse_${user.id}" aria-expanded="false" aria-controls="collapseExample"
                                data-id="${user.id}"
                                >
                                Connections in common (${user.common_connections_count})
                              </button>
                              <button class="btn btn-danger me-1 remove_connection_btn" data-id="${user.id}">Remove Connection</button>
                            </div>

                          </div>
                          <div class="collapse" id="collapse_${user.id}">
                            <div class="p-2 common-connections-content"></div>
                            <div class="connections_in_common_skeletons"></div>
                            <div class="d-flex justify-content-center w-100 py-2 load_more_connections_in_common_parent">
                              <button class="btn btn-sm btn-primary load_more_connections_in_common">Load more</button>
                            </div>
                          </div>
                        </div>

                    `);

                skeleton.addClass('d-none');
            })

            if (nextPage !== null) {
                load_more_btn_parent.removeClass('d-none');
                pageUrl = nextPage;
            } else {
                load_more_btn_parent.addClass('d-none');
            }
        }).catch((error => {
            console.log(error)
        }))
    }
}

function getMoreConnectionsInCommon() {
    let pageUrl;

    $(document).on('click', '.load_more_connections_in_common', (el) => {
        getData(pageUrl, $(el.target));
    });

    $(document).on('click', '.get_connections_in_common:not(.collapsed)', (el) => {
        const user_id = $(el.target).data('id');
        const connection = $(el.target).closest('.connection');
        const common_connections_content = connection.find('.common-connections-content');
        const common_connections_load_more_btn = connection.find('.load_more_connections_in_common');

        common_connections_content.html('');

        getData(`/connections/common/${user_id}`, common_connections_load_more_btn);
    });

    function getData(url, btn) {
        if (url === undefined) return;

        const common_connections_skeleton = btn.closest('.connection').find('.connections_in_common_skeletons');

        for(let i = 0; i < 10; i++) {
            common_connections_skeleton.append(`
                <div class="d-flex align-items-center  mb-2  text-white bg-dark p-1 shadow" style="height: 45px">
                  <strong class="ms-1 text-primary">Loading...</strong>
                  <div class="spinner-border ms-auto text-primary me-4" role="status" aria-hidden="true"></div>
                </div>
            `);
        }

        common_connections_skeleton.removeClass('d-none');

        axios.get(url).then((rsp) => {
            const nextPage = rsp.data.links.next;

            if (rsp.status === 200) {
                rsp.data.data.forEach((user) => {
                    btn.closest('.connection').find('.common-connections-content').append(`
                        <div class="p-2 shadow rounded mt-2 text-white bg-dark">${user.name} - ${user.email}</div>
                    `);
                });

                common_connections_skeleton.addClass('d-none');

                if (nextPage !== null) {
                    btn.closest('.load_more_connections_in_common_parent').removeClass('d-none');
                    pageUrl = nextPage;
                } else {
                    btn.closest('.load_more_connections_in_common_parent').addClass('d-none');
                }
            }
        }).catch((error => {
            console.log(error)
        }))
    }
}

function getSuggestions() {
    let pageUrl;

    load_more_btn.on('click', () => {
        if ($('input[name="active_tab"]:checked').val() === 'suggestions') {
            getData(pageUrl);
        }
    });

    $('#get_suggestions_btn').on('click', () => {
        content.html('');
        getData('/suggestions');
    });

    function getData(url) {
        if (url === undefined) return;

        skeleton.removeClass('d-none');

        axios.get(url).then((rsp) => {
            const nextPage = rsp.data.links.next;

            suggestions_count.text(rsp.data.meta.total);

            rsp.data.data.forEach((user) => {
                content.append(`
                    <div class="my-2 shadow text-white bg-dark p-1 suggestion">
                      <div class="d-flex justify-content-between">
                        <table class="ms-1">
                          <td class="align-middle">${user.name}</td>
                          <td class="align-middle"> - </td>
                          <td class="align-middle">${user.email}</td>
                          <td class="align-middle">
                        </table>
                        <div>
                          <button class="btn btn-primary me-1 create_request_btn" data-id="${user.id}">Connect</button>
                        </div>
                      </div>
                    </div>
                `);
            })

            if (rsp.data.data.length === 0) {
                content.append(`
                    <span class="fw-bold">There are no records.</span>
                `);
            }

            skeleton.addClass('d-none');

            if (nextPage !== null) {
                load_more_btn_parent.removeClass('d-none');
                pageUrl = nextPage;
            } else {
                load_more_btn_parent.addClass('d-none');
            }
        }).catch((error => {
            console.log(error)
        }))
    }
}

function sendRequest() {
    $(document).on('click', '.create_request_btn', (el) => {
        const user_id = $(el.target).data('id');

        axios.post(`/requests`, {
            'user_id': user_id
        }).then((rsp) => {
            if (rsp.status === 200) {
                $(el.target).closest('.suggestion').addClass('d-none');

                suggestions_count.text(
                    parseInt(suggestions_count.text()) - 1
                );

                sent_requests_count.text(
                    parseInt(sent_requests_count.text()) + 1
                );
            }
        }).catch((error => {
            console.log(error)
        }))
    });
}

function deleteRequest() {
    $(document).on('click', '.cancel_request_btn', (el) => {
        const request_id = $(el.target).data('id');

        axios.delete(`/requests/${request_id}`).then((rsp) => {
            if (rsp.status === 200) {
                $(el.target).closest('.sent-request').addClass('d-none');

                sent_requests_count.text(
                    parseInt(sent_requests_count.text()) - 1
                );

                suggestions_count.text(
                    parseInt(suggestions_count.text()) + 1
                );
            }
        }).catch((error => {
            console.log(error)
        }))
    });
}

function acceptRequest() {
    $(document).on('click', '.accept_request_btn', (el) => {
        const request_id = $(el.target).data('id');

        axios.post(`/requests/${request_id}/accept`).then((rsp) => {
            if (rsp.status === 200) {
                $(el.target).closest('.received-request').addClass('d-none');

                received_requests_count.text(
                    parseInt(received_requests_count.text()) - 1
                );

                connections_count.text(
                    parseInt(connections_count.text()) + 1
                );
            }
        }).catch((error => {
            console.log(error)
        }))
    });
}

function removeConnection() {
    $(document).on('click', '.remove_connection_btn', (el) => {
        const user_id = $(el.target).data('id');

        axios.delete(`/connections/${user_id}`).then((rsp) => {
            if (rsp.status === 200) {
                $(el.target).closest('.connection').addClass('d-none');

                connections_count.text(
                    parseInt(connections_count.text()) - 1
                );
            }
        }).catch((error => {
            console.log(error)
        }))
    });
}

$(function () {
    getSentRequests();
    getReceivedRequests();
    getConnections();
    getSuggestions();
    sendRequest();
    acceptRequest();
    deleteRequest();
    getMoreConnectionsInCommon();
    removeConnection();
});
