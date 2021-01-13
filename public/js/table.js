'use strict';

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

var SORT_ASC = 'ASC';
var SORT_DESC = 'DESC';

var Table = function Table() {
    var conditionsData = ['равно', 'содержить', 'больше', 'меньше'];
    var columns = [{ name: 'date', label: 'Дата', sorting: false }, { name: 'name', label: 'Название', sorting: true }, { name: 'quantity', label: 'Количество', sorting: true }, { name: 'distance', label: 'Расстояние', sorting: true }];
    var limit = 10;

    var _React$useState = React.useState([]),
        _React$useState2 = _slicedToArray(_React$useState, 2),
        data = _React$useState2[0],
        setData = _React$useState2[1];

    var _React$useState3 = React.useState(0),
        _React$useState4 = _slicedToArray(_React$useState3, 2),
        countData = _React$useState4[0],
        setCountData = _React$useState4[1];

    var _React$useState5 = React.useState({
        column: '',
        type: ''
    }),
        _React$useState6 = _slicedToArray(_React$useState5, 2),
        sort = _React$useState6[0],
        setSort = _React$useState6[1];

    var _React$useState7 = React.useState(''),
        _React$useState8 = _slicedToArray(_React$useState7, 2),
        column = _React$useState8[0],
        setColumn = _React$useState8[1];

    var _React$useState9 = React.useState(''),
        _React$useState10 = _slicedToArray(_React$useState9, 2),
        conditions = _React$useState10[0],
        setConditions = _React$useState10[1];

    var _React$useState11 = React.useState(''),
        _React$useState12 = _slicedToArray(_React$useState11, 2),
        filterValues = _React$useState12[0],
        setFilterValues = _React$useState12[1];

    var _React$useState13 = React.useState(false),
        _React$useState14 = _slicedToArray(_React$useState13, 2),
        loader = _React$useState14[0],
        setLoader = _React$useState14[1];

    var _React$useState15 = React.useState(1),
        _React$useState16 = _slicedToArray(_React$useState15, 2),
        page = _React$useState16[0],
        setPage = _React$useState16[1];

    var _React$useState17 = React.useState(0),
        _React$useState18 = _slicedToArray(_React$useState17, 2),
        lastPage = _React$useState18[0],
        setLastPage = _React$useState18[1];

    var _React$useState19 = React.useState(false),
        _React$useState20 = _slicedToArray(_React$useState19, 2),
        error = _React$useState20[0],
        setError = _React$useState20[1];

    React.useEffect(function () {
        getData();
    }, [sort, page]);

    React.useEffect(function () {
        if (conditionsData.length) {
            setConditions(conditionsData[0]);
        }
        if (columns.length) {
            setColumn(columns[0].name);
        }
    }, []);

    var Loader = function Loader() {
        return React.createElement(
            'div',
            {
                className: 'spinner-border text-primary',
                style: { position: 'absolute', top: "calc(50% - 2em)", left: "calc(50% - 2em)" },
                role: 'status'
            },
            React.createElement(
                'span',
                { className: 'sr-only' },
                'Loading...'
            )
        );
    };
    var getData = function getData() {
        setLoader(true);
        var url = '/getData?orderby=' + sort.column + '&ordertype=' + sort.type + '&column=' + column + '&conditions=' + conditions + '&value=' + filterValues + '&page=' + page;
        fetch(url).then(function (response) {
            return response.json();
        }).then(function (data) {
            setData(data.data);
            setLastPage(data.last_page);
            setCountData(data.count_data);
            setLoader(false);
        }).catch(function () {
            setLoader(false);
            setError(true);
        });
    };
    var handleSort = function handleSort(column) {
        if (sort.column === column) {
            if (sort.type === SORT_ASC) {
                setSort(function (prev) {
                    return Object.assign({}, prev, { type: SORT_DESC });
                });
            } else {
                setSort(function (prev) {
                    return Object.assign({}, prev, { type: SORT_ASC });
                });
            }
        } else {
            setSort(function () {
                return { column: column, type: SORT_ASC };
            });
        }
    };

    var tableData = function tableData() {
        var rows = [];
        data.map(function (_ref) {
            var id = _ref.id,
                date = _ref.date,
                name = _ref.name,
                quantity = _ref.quantity,
                distance = _ref.distance;

            rows.push(React.createElement(
                'tr',
                { key: id.toString() },
                React.createElement(
                    'td',
                    null,
                    date
                ),
                React.createElement(
                    'td',
                    null,
                    name
                ),
                React.createElement(
                    'td',
                    null,
                    quantity
                ),
                React.createElement(
                    'td',
                    null,
                    distance
                )
            ));
        });
        return rows;
    };

    var onSubmit = function onSubmit(event) {
        event.preventDefault();
        setPage(1);
        if (page === 1) {
            getData();
        }
    };

    var Filters = function Filters() {
        return React.createElement(
            'form',
            { onSubmit: onSubmit },
            React.createElement(
                'div',
                { className: 'row', style: { alignItems: 'stretch' } },
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { htmlFor: 'column' },
                            '\u0412\u044B\u0431\u043E\u0440 \u043A\u043E\u043B\u043E\u043D\u043A\u0438'
                        ),
                        React.createElement(
                            'select',
                            {
                                className: 'form-control',
                                id: 'column',
                                onChange: function onChange(event) {
                                    return setColumn(event.target.value);
                                } },
                            columns.map(function (_ref2, i) {
                                var label = _ref2.label,
                                    name = _ref2.name;

                                return React.createElement(
                                    'option',
                                    { key: name.toString(), value: name },
                                    label
                                );
                            })
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-2' },
                    React.createElement(
                        'div',
                        { className: 'form-group' },
                        React.createElement(
                            'label',
                            { htmlFor: 'conditions' },
                            '\u0412\u044B\u0431\u043E\u0440 \u0443\u0441\u043B\u043E\u0432\u0438\u044F'
                        ),
                        React.createElement(
                            'select',
                            {
                                className: 'form-control',
                                id: 'conditions',
                                onChange: function onChange(event) {
                                    return setConditions(event.target.value);
                                }
                            },
                            conditionsData.map(function (conditions, i) {
                                return React.createElement(
                                    'option',
                                    { value: conditions, key: conditions.toString() },
                                    conditions
                                );
                            })
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-8', style: { display: 'flex', alignItems: 'flex-end' } },
                    React.createElement(
                        'div',
                        { className: 'input-group', style: { marginBottom: "1rem" } },
                        React.createElement('input', {
                            type: 'text',
                            className: 'form-control',
                            placeholder: '\u041F\u043E\u043B\u0435 \u0434\u043B\u044F \u0432\u0432\u043E\u0434\u0430 \u0437\u043D\u0430\u0447\u0435\u043D\u0438\u044F',
                            value: filterValues,
                            onChange: function onChange(event) {
                                return setFilterValues(event.target.value);
                            }
                        }),
                        React.createElement(
                            'span',
                            { className: 'input-group-btn' },
                            React.createElement(
                                'button',
                                { className: 'btn btn-default', type: 'submit' },
                                React.createElement('i', { className: 'fa fa-search' })
                            )
                        )
                    )
                )
            )
        );
    };

    var Sorting = function Sorting(column) {
        var activeAsc = '';
        var activeDesc = '';
        if (column === sort.column && sort.type === SORT_ASC) {
            activeAsc = 'text-dark';
        } else if (column === sort.column && sort.type === SORT_DESC) {
            activeDesc = 'text-dark';
        }

        return React.createElement(
            'div',
            { className: 'float-right cursor-pointer', onClick: function onClick() {
                    return handleSort(column);
                } },
            React.createElement('i', { className: 'fas fa-arrow-up mr-2 ' + activeAsc }),
            React.createElement('i', { className: 'fas fa-arrow-down ' + activeDesc })
        );
    };
    var th = function th(_ref3) {
        var label = _ref3.label,
            name = _ref3.name,
            sorting = _ref3.sorting;

        return React.createElement(
            'th',
            { key: name.toString() },
            label,
            sorting ? Sorting(name) : null
        );
    };
    var createPaginationLink = function createPaginationLink(start, count) {
        var links = [];
        for (var i = start; i <= count; i++) {
            links.push(paginationLink(i));
        }
        return links;
    };

    var onClickPaginationLink = function onClickPaginationLink(value, e) {
        e.preventDefault();
        if (value === 'Next' && page < lastPage) {
            setPage(function (prev) {
                return prev + 1;
            });
        } else if (value === 'Previous' && page > 1) {
            setPage(function (prev) {
                return prev - 1;
            });
        } else if (Number.isInteger(value)) {
            setPage(value);
        } else {
            return false;
        }
    };
    var paginationLink = function paginationLink(value) {
        var active = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

        return React.createElement(
            'li',
            { key: active + value.toString(), onClick: function onClick(e) {
                    return onClickPaginationLink(value, e);
                },
                className: 'page-item ' + active },
            React.createElement(
                'a',
                { className: 'page-link',
                    href: '#' },
                value
            )
        );
    };

    var Pagination = function Pagination() {
        var link = 3;
        var result = {
            previous: [],
            startPage: [],
            endPage: [],
            next: [],
            link: link
        };

        if (countData < limit || page > lastPage) {
            return null;
        }

        if (page > link + 1) {
            var tempNumber = lastPage - page;
            if (tempNumber <= link) {
                link += link - tempNumber;
                tempNumber = lastPage - link;
                if (tempNumber < 2) {
                    if (lastPage !== page) {
                        link = lastPage - 2;
                    } else {
                        link = lastPage - 1;
                    }
                }
            }
            result.previous.push(createPaginationLink(page - link, page - 1));
        } else {
            result.previous.push(createPaginationLink(1, page - 1));
        }
        if (result.previous[0].length >= 3 && page > 4 && lastPage > link * 2 + 1) {
            result.startPage.push(React.createElement(
                React.Fragment,
                { key: 'first_page' },
                paginationLink(1, 'start-page'),
                paginationLink('...', 'start-dots')
            ));
        }

        if (page + link < lastPage) {
            var tempData = page - 1;
            if (tempData <= link) {
                link += link - tempData;
                tempData = lastPage - link;
                if (tempData < 2) {
                    if (1 !== page) {
                        link = lastPage - 2;
                    } else {
                        link = lastPage - 1;
                    }
                }
            }
            result.next.push(createPaginationLink(page + 1, page + link));
        } else {
            result.next.push(createPaginationLink(page + 1, lastPage));
        }
        if (result.next[0].length >= 3 && lastPage - page > 3 && lastPage > link * 2 + 1) {
            result.endPage.push(React.createElement(
                React.Fragment,
                { key: 'last_page' },
                paginationLink('...', 'end-dots'),
                paginationLink(lastPage, 'last-page')
            ));
        }
        return renderPagination(result);
    };

    var renderPagination = function renderPagination(data) {
        return React.createElement(
            'nav',
            { 'aria-label': 'Page navigation example' },
            React.createElement(
                'ul',
                { className: 'pagination' },
                page > 1 ? paginationLink('Previous') : null,
                data.startPage,
                data.previous,
                paginationLink(page, 'active'),
                data.next,
                data.endPage,
                page < lastPage ? paginationLink('Next') : null
            )
        );
    };
    var Alert = function Alert(text) {
        return React.createElement(
            'div',
            { className: 'alert alert-danger', role: 'alert' },
            text
        );
    };

    return React.createElement(
        'div',
        { className: 'container py-5 ' },
        error && Alert('Что-то пошло не так'),
        Filters(),
        React.createElement(
            'div',
            null,
            React.createElement(
                'table',
                { className: 'table position-relative' },
                React.createElement(
                    'thead',
                    null,
                    React.createElement(
                        'tr',
                        null,
                        columns.map(function (column) {
                            return th(column);
                        })
                    )
                ),
                React.createElement(
                    'tbody',
                    null,
                    tableData()
                )
            ),
            React.createElement(
                'div',
                { className: 'd-flex justify-content-center' },
                Pagination()
            ),
            loader ? Loader() : null
        )
    );
};

var domContainer = document.querySelector('#app');
ReactDOM.render(React.createElement(Table, null), domContainer);