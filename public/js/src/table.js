'use strict';

const SORT_ASC = 'ASC'
const SORT_DESC = 'DESC'

const Table = () => {
    const conditionsData = ['равно', 'содержить', 'больше', 'меньше']
    const columns = [
        {name: 'date', label: 'Дата', sorting: false},
        {name: 'name', label: 'Название', sorting: true},
        {name: 'quantity', label: 'Количество', sorting: true},
        {name: 'distance', label: 'Расстояние', sorting: true}
    ]
    const limit = 10;

    const [data, setData] = React.useState([])
    const [countData, setCountData] = React.useState(0)
    const [sort, setSort] = React.useState({
        column: '',
        type: ''
    })
    const [column, setColumn] = React.useState('')
    const [conditions, setConditions] = React.useState('')
    const [filterValues, setFilterValues] = React.useState('')
    const [loader, setLoader] = React.useState(false)
    const [page, setPage] = React.useState(1)
    const [lastPage, setLastPage] = React.useState(0)
    const [error, setError] = React.useState(false)


    React.useEffect(() => {
        getData()
    }, [sort, page])

    React.useEffect(() => {
        if (conditionsData.length) {
            setConditions(conditionsData[0])
        }
        if (columns.length) {
            setColumn(columns[0].name)
        }

    }, [])

    const Loader = () => {
        return (
            <div
                className="spinner-border text-primary"
                style={{position: 'absolute', top: "calc(50% - 2em)", left: "calc(50% - 2em)"}}
                role="status"
            >
                <span className="sr-only">Loading...</span>
            </div>
        )
    }
    const getData = () => {
        setLoader(true)
        const url = `/getData?orderby=${sort.column}&ordertype=${sort.type}&column=${column}&conditions=${conditions}&value=${filterValues}&page=${page}`
        fetch(url)
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                setData(data.data);
                setLastPage(data.last_page)
                setCountData(data.count_data)
                setLoader(false)

            }).catch(()=>{
                setLoader(false)
                setError(true)
        });
    }
    const handleSort = (column) => {
        if (sort.column === column) {
            if (sort.type === SORT_ASC) {
                setSort(prev => ({...prev, type: SORT_DESC}))
            } else {
                setSort(prev => ({...prev, type: SORT_ASC}))
            }
        } else {
            setSort(() => ({column: column, type: SORT_ASC}))
        }
    }

    const tableData = () => {
        const rows = []
        data.map(({id, date, name, quantity, distance}) => {
            rows.push(
                <tr key={id.toString()}>
                    <td>{date}</td>
                    <td>{name}</td>
                    <td>{quantity}</td>
                    <td>{distance}</td>
                </tr>
            )
        })
        return rows
    }

    const onSubmit = event => {
        event.preventDefault()
        setPage(1)
        if (page === 1){
            getData()
        }
    }

    const Filters = () => {
        return (
            <form onSubmit={onSubmit}>
                <div className={'row'} style={{alignItems: 'stretch'}}>
                    <div className={'col-md-2'}>
                        <div className={'form-group'}>
                            <label htmlFor={'column'}>Выбор колонки</label>
                            <select
                                className={'form-control'}
                                id={'column'}
                                onChange={(event) => setColumn(event.target.value)}>
                                {columns.map(({label, name}, i) => {
                                    return (
                                        <option key={name.toString()} value={name}>{label}</option>
                                    )
                                })}
                            </select>
                        </div>
                    </div>
                    <div className={'col-md-2'}>
                        <div className={'form-group'}>
                            <label htmlFor={'conditions'}>Выбор условия</label>
                            <select
                                className={'form-control'}
                                id={'conditions'}
                                onChange={(event) => setConditions(event.target.value)}
                            >
                                {conditionsData.map((conditions, i) => {
                                    return (
                                        <option value={conditions} key={conditions.toString()}>{conditions}</option>
                                    )
                                })}
                            </select>
                        </div>
                    </div>
                    <div className={'col-md-8'} style={{display: 'flex', alignItems: 'flex-end'}}>
                        <div className="input-group" style={{marginBottom: "1rem"}}>
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Поле для ввода значения"
                                value={filterValues}
                                onChange={(event) => setFilterValues(event.target.value)}
                            />
                            <span className="input-group-btn">
                            <button className="btn btn-default" type="submit">
                                <i className="fa fa-search"></i>
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
            </form>
        )
    }

    const Sorting = (column) => {
        let activeAsc = ''
        let activeDesc = ''
        if (column === sort.column && sort.type === SORT_ASC) {
            activeAsc = 'text-dark'
        } else if (column === sort.column && sort.type === SORT_DESC) {
            activeDesc = 'text-dark'
        }

        return (
            <div className={'float-right cursor-pointer'} onClick={() => handleSort(column)}>
                <i className={`fas fa-arrow-up mr-2 ${activeAsc}`}></i>
                <i className={`fas fa-arrow-down ${activeDesc}`}></i>
            </div>
        )
    }
    const th = ({label, name, sorting}) => {
        return (
            <th key={name.toString()}>
                {label}
                {sorting ? Sorting(name) : null}
            </th>
        )
    }
    const createPaginationLink = (start, count) => {
        const links = []
        for (let i = start; i <= count; i++) {
            links.push(paginationLink(i))
        }
        return links
    }

    const onClickPaginationLink = (value, e) => {
        e.preventDefault()
        if (value === 'Next' && page < lastPage) {
            setPage((prev) => prev + 1)
        } else if (value === 'Previous' && page > 1) {
            setPage((prev) => prev - 1)
        } else if (Number.isInteger(value)) {
            setPage(value)
        } else {
            return false
        }
    }
    const paginationLink = (value,active = '') => {
        return (
            <li key={active + value.toString()} onClick={(e) => onClickPaginationLink(value, e)}
                className={`page-item ${active}`}><a className="page-link"
                                                                             href="#">{value}</a></li>
        )
    }

    const Pagination = () => {
        let link = 3
        const result = {
            previous: [],
            startPage: [],
            endPage: [],
            next: [],
            link: link
        }

        if (countData < limit || page > lastPage) {
            return null;
        }

        if (page > link + 1) {
            let tempNumber = lastPage - page
            if (tempNumber <= link) {
                link += link - tempNumber
                tempNumber = lastPage - link
                if (tempNumber < 2) {
                    if (lastPage !== page){
                        link = lastPage - 2

                    }else {
                        link = lastPage - 1
                    }
                }
            }
                result.previous.push(createPaginationLink(page - link, page - 1))
            } else {
                result.previous.push(createPaginationLink(1, page - 1))
            }
            if (result.previous[0].length >= 3 && page > 4 && lastPage > (link * 2) + 1) {
                result.startPage.push(
                    <React.Fragment key={'first_page'}>
                        {paginationLink(1, 'start-page')}
                        {paginationLink('...', 'start-dots')}
                    </React.Fragment>
                )
            }

            if (page + link < lastPage) {
                let tempData = page - 1
                if (tempData <= link) {
                    link += link - tempData;
                    tempData = lastPage - link
                    if (tempData < 2){
                        if (1 !== page){
                            link = lastPage - 2

                        }else {
                            link = lastPage - 1
                        }
                    }
                }
                result.next.push(createPaginationLink(page + 1, page + link))
            } else {
                result.next.push(createPaginationLink(page + 1, lastPage))
            }
            if (result.next[0].length >= 3 && lastPage - page > 3 && lastPage > (link * 2) + 1) {
                result.endPage.push(
                    <React.Fragment key={'last_page'}>
                        {paginationLink('...', 'end-dots')}
                        {paginationLink(lastPage, 'last-page')}
                    </React.Fragment>
                )
            }
            return renderPagination(result)
    }

    const renderPagination = (data) => {
        return (
            <nav aria-label="Page navigation example">
                <ul className="pagination">
                    {(page > 1) ? paginationLink('Previous') : null}
                    {data.startPage}
                    {data.previous}
                    {paginationLink(page,'active')}
                    {data.next}
                    {data.endPage}
                    {(page < lastPage) ? paginationLink('Next') : null}
                </ul>
            </nav>
        )
    }
    const Alert = (text) => {
        return (
            <div className="alert alert-danger" role="alert">
                {text}
            </div>
        )
    }

    return (
        <div className={'container py-5 '}>
            {error && Alert('Что-то пошло не так')}
            {Filters()}
            <div>
                <table className={'table position-relative'}>
                    <thead>
                    <tr>
                        {columns.map((column) => {
                            return th(column)
                        })}
                    </tr>
                    </thead>
                    <tbody>
                    {tableData()}
                    </tbody>
                </table>
                <div className={'d-flex justify-content-center'}>
                    {Pagination()}
                </div>
                {loader ? Loader() : null}
            </div>
        </div>
    );
}

const domContainer = document.querySelector('#app');
ReactDOM.render(<Table/>, domContainer);
