import { useState, useEffect } from 'react';

import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Table from '@mui/material/Table';
import Container from '@mui/material/Container';
import TableBody from '@mui/material/TableBody';
import Typography from '@mui/material/Typography';
import TableContainer from '@mui/material/TableContainer';
import TablePagination from '@mui/material/TablePagination';

import { useRouter } from 'src/routes/hooks';

import useAxiosFetch from 'src/hooks/use-axios';

import QueryDrawer from 'src/drawers/add_query';

import Scrollbar from 'src/components/scrollbar';
import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

import TableNoData from '../table-no-data';
import QueryTableRow from '../query-table-row';
import UserTableHead from '../user-table-head';
import TableEmptyRows from '../table-empty-rows';
import UserTableToolbar from '../user-table-toolbar';
import { emptyRows, applyFilter, getComparator } from '../utils';

// ----------------------------------------------------------------------

export default function QueryPage() {

  const router = useRouter();

  const [page, setPage] = useState(0);

  const [order, setOrder] = useState('asc');

  const [selected, setSelected] = useState([]);

  const [queries, setQueries] = useState([]);

  const [orderBy, setOrderBy] = useState('name');

  const [filterName, setFilterName] = useState('');

  const [rowsPerPage, setRowsPerPage] = useState(5);

  const [user, setUser] = useState({});

  const { isLoading: isLoadingQueries, isError: isErrorQueries, data: dataQueries,statusCode:statusCodeQueries } = useAxiosFetch('getqueries','GET', {});

  useEffect(() => {
    if(dataQueries && statusCodeQueries===200)
    {
      setQueries(dataQueries.queries);    
      setUser(dataQueries.user)
    }   
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataQueries]); 

  const handleSort = (event, id) => {
    const isAsc = orderBy === id && order === 'asc';
    if (id !== '') {
      setOrder(isAsc ? 'desc' : 'asc');
      setOrderBy(id);
    }
  };

  const handleSelectAllClick = (event) => {
    if (event.target.checked) {
      const newSelecteds = queries.map((n) => n.id);
      setSelected(newSelecteds);
      return;
    }
    setSelected([]);
  };

  const handleClick = (event, name) => {
    const selectedIndex = selected.indexOf(name);
    let newSelected = [];
    if (selectedIndex === -1) {
      newSelected = newSelected.concat(selected, name);
    } else if (selectedIndex === 0) {
      newSelected = newSelected.concat(selected.slice(1));
    } else if (selectedIndex === selected.length - 1) {
      newSelected = newSelected.concat(selected.slice(0, -1));
    } else if (selectedIndex > 0) {
      newSelected = newSelected.concat(
        selected.slice(0, selectedIndex),
        selected.slice(selectedIndex + 1)
      );
    }
     setSelected(newSelected);
  };

  const handleViewClick = (e,id) =>{
    router.push(`/query-details/${id}`);
  }

  const handleChangePage = (event, newPage) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (event) => {
    setPage(0);
    setRowsPerPage(parseInt(event.target.value, 10));
  };

  const handleFilterByName = (event) => {
    setPage(0);
    setFilterName(event.target.value);
  };

  const dataFiltered = applyFilter({
    inputData: queries,
    comparator: getComparator(order, orderBy),
    filterName,
  });

  const notFound = !dataFiltered.length && !!filterName;


  return (
    <Container>
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
        <Typography variant="h4">My Queries</Typography>
        {isLoadingQueries?<Loader/>:null}
        

       <QueryDrawer myvariant="contained" mycolor="inherit" mytext="Add Query" plan={user.plan}/>
      </Stack>
      {isErrorQueries?<Error mymessage={dataQueries.message}/>:null}
      <Card>
        <UserTableToolbar
          numSelected={selected.length}
          filterName={filterName}
          onFilterName={handleFilterByName}
        />

        <Scrollbar>
          <TableContainer sx={{ overflow: 'unset' }}>
            <Table sx={{ minWidth: 800 }}>
              <UserTableHead
                order={order}
                orderBy={orderBy}
                rowCount={queries.length}
                numSelected={selected.length}
                onRequestSort={handleSort}
                onSelectAllClick={handleSelectAllClick}
                headLabel={[
                  { id: 'description', label: 'Description' },
                  { id: 'category', label: 'Category' },
                  { id: 'date_entered', label: 'Date Entered' },
                  { id: 'source', label: 'Source' },
                  { id: 'documents', label: 'Document(s)?', align: 'center' },
                  { id: 'status', label: 'Status' },
                  { id: '' },
                ]}
              />
              <TableBody>
                {dataFiltered
                  .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                  .map((row) => (
                    <QueryTableRow
                      key={row.id}
                      description={row.description}
                      category={row.category}
                      date_entered={row.date_entered}
                      source={row.source}
                      documents={row.status}
                      status={row.status}                      
                      selected={selected.indexOf(row.id) !== -1}
                      handleClick={(event) => handleClick(event, row.id)}
                      handleViewClick={(e) => handleViewClick(e, row.id)}
                    />
                  ))}

                <TableEmptyRows
                  height={77}
                  emptyRows={emptyRows(page, rowsPerPage, queries.length)}
                />

                {notFound && <TableNoData/>}
                {dataFiltered.length<1 && filterName===""?<TableNoData/>:null}
              </TableBody>
            </Table>
          </TableContainer>
        </Scrollbar>

        <TablePagination
          page={page}
          component="div"
          count={queries.length}
          rowsPerPage={rowsPerPage}
          onPageChange={handleChangePage}
          rowsPerPageOptions={[5, 10, 25]}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </Card>
    </Container>
  );
}
