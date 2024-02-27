import { Modal } from 'antd';
import { useState, useEffect } from 'react';

import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Table from '@mui/material/Table';
import Container from '@mui/material/Container';
import TableBody from '@mui/material/TableBody';
import Typography from '@mui/material/Typography';
import TableContainer from '@mui/material/TableContainer';
import TablePagination from '@mui/material/TablePagination';

import useAxiosFetch from 'src/hooks/use-axios';

import { account } from 'src/_mock/account';

import Scrollbar from 'src/components/scrollbar';
import Loader from 'src/components/response/loader';

import TableNoData from '../table-no-data';
import ClaimsTableRow from '../claims-row';
import UserTableHead from '../user-table-head';
import TableEmptyRows from '../table-empty-rows';
import UserTableToolbar from '../user-table-toolbar';
import { emptyRows, applyFilter, getComparator } from '../utils';

// ----------------------------------------------------------------------

export default function TipsPage() {


  const [isModalOpen, setIsModalOpen] = useState(false);

  const [page, setPage] = useState(0);

  const [order, setOrder] = useState('asc');

  const [claims, setClaims] = useState([]);

  const [selected, setSelected] = useState([]);

  const [orderBy, setOrderBy] = useState('name');

  const [filterName, setFilterName] = useState('');

  const [rowsPerPage, setRowsPerPage] = useState(5);

  const { isLoading: isLoadingClaims, isError: isErrorClaims, data: dataClaims } = useAxiosFetch('getclaims','GET', {'id':account.user.id,'email':account.user.email,claim_id:0,'type':'internal','scheme_number':account.user.scheme_number,'id_number':account.user.id_number});

  useEffect(() => {
    if(dataClaims)
    {
      console.log("Claims Load");
      console.log(dataClaims);  
      setClaims(dataClaims.claims);    
    }   
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataClaims]); 


  const handleSort = (event, id) => {
    const isAsc = orderBy === id && order === 'asc';
    if (id !== '') {
      setOrder(isAsc ? 'desc' : 'asc');
      setOrderBy(id);
    }
  };

  const handleViewClick = (e,id) =>{
    console.log(id);
    setIsModalOpen(true);
  }

  const handleSelectAllClick = (event) => {
    if (event.target.checked) {
      const newSelecteds = claims.map((n) => n.name);
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
    inputData: claims,
    comparator: getComparator(order, orderBy),
    filterName,
  });

 
  const handleOk = () => {
    setIsModalOpen(false);
  };
  const handleCancel = () => {
    setIsModalOpen(false);
  };

  const notFound = !dataFiltered.length && !!filterName;

  return (
    <Container>
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
      <Typography variant="h4">Tips</Typography>
        {isLoadingClaims?<Loader/>:null}
        {isErrorClaims?<Typography variant="h6">There is an error</Typography>:null}
        
      </Stack>

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
                rowCount={claims.length}
                numSelected={selected.length}
                onRequestSort={handleSort}
                onSelectAllClick={handleSelectAllClick}
                headLabel={[
                  { id: 'claim_number', label: 'Claim Number' },
                  { id: 'medical_scheme', label: 'Medical Scheme' },
                  { id: 'date_entered', label: 'Date Entered' },
                  { id: 'charged_amnt', label: 'Charged Amount', align: 'center' },
                  { id: 'Open', label: 'Status' },
                  { id: '' },
                ]}
              />
              <TableBody>
                {dataFiltered
                  .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                  .map((row) => (
                    <ClaimsTableRow
                      key={row.claim_number}
                      claim_number={row.claim_number}
                      medical_scheme={row.medical_scheme}
                      date_entered={row.date_entered}                     
                      charged_amnt={row.charged_amnt}
                      Open={row.Open}
                      selected={selected.indexOf(row.claim_number) !== -1}
                      handleClick={(event) => handleClick(event, row.claim_number)}
                      handleViewClick={(event) => handleViewClick(event, row.claim_id)}
                    />
                  ))}

                <TableEmptyRows
                  height={77}
                  emptyRows={emptyRows(page, rowsPerPage, claims.length)}
                />

                {notFound && <TableNoData query={filterName} />}
              </TableBody>
            </Table>
          </TableContainer>
        </Scrollbar>

        <TablePagination
          page={page}
          component="div"
          count={claims.length}
          rowsPerPage={rowsPerPage}
          onPageChange={handleChangePage}
          rowsPerPageOptions={[5, 10, 25]}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </Card>
      <Modal title="Claim tips" open={isModalOpen} onOk={handleOk} onCancel={handleCancel}>
        <p>No Tips</p>        
      </Modal>
    </Container>
    
  );
}
