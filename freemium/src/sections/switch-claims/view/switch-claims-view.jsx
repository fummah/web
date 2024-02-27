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

import { useRouter } from 'src/routes/hooks';

import useAxiosFetch from 'src/hooks/use-axios';

import { account } from 'src/_mock/account';

import Scrollbar from 'src/components/scrollbar';
import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

import TableNoData from '../table-no-data';
import UserTableHead from '../user-table-head';
import TableEmptyRows from '../table-empty-rows';
import ClaimsTableRow from '../switch-claims-row';
import UserTableToolbar from '../user-table-toolbar';
import { emptyRows, applyFilter, getComparator } from '../utils';

// ----------------------------------------------------------------------

export default function ClaimsPage() {

  const router = useRouter();

  const [isModalOpen, setIsModalOpen] = useState(false);

  const [page, setPage] = useState(0);

  const [order, setOrder] = useState('asc');

  const [claims, setClaims] = useState([]);

  const [selected, setSelected] = useState([]);

  const [orderBy, setOrderBy] = useState('name');

  const [filterName, setFilterName] = useState('');

  const [rowsPerPage, setRowsPerPage] = useState(5);

  const { isLoading: isLoadingClaims, isError: isErrorClaims, data: dataClaims,statusCode:statusCodeClaims } = useAxiosFetch('getclaims','GET', {'id':account.user.id,'email':account.user.email,"claim_id":0,'type':'external','scheme_number':account.user.scheme_number,'id_number':account.user.id_number});

  useEffect(() => {
    if(dataClaims && statusCodeClaims===200)
    {
      console.log("Claims Load");
      console.log(dataClaims);  
      setClaims(dataClaims);    
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
    router.push(`/claim-details/${id}/external`);
  }

  const handleTipClick = (e,id) =>{
    console.log(id);
    setIsModalOpen(true);
  }

  const handleSelectAllClick = (event) => {
    if (event.target.checked) {
      const newSelecteds = claims.map((n) => n.claim_number);
      setSelected(newSelecteds);
      return;
    }
    setSelected([]);
  };

  const handleOk = () => {
    setIsModalOpen(false);
  };
  const handleCancel = () => {
    setIsModalOpen(false);
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

  const notFound = !dataFiltered.length && !!filterName;

  return (
    <Container>
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
        <Typography variant="h4">Identified Claims</Typography>
        {isLoadingClaims?<Loader/>:null}
        
      </Stack>
      {isErrorClaims?<Error mymessage={dataClaims.message}/>:null}
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
                  { id: 'doctors', label: 'Provider(s)' },
                  { id: 'service_date', label: 'Service Date' },
                  { id: 'charged_amnt', label: 'Charged Amount', align: 'center' },
                  { id: 'scheme_paid', label: 'Scheme Amount', align: 'center' },
                  { id: 'gap', label: 'Short paid', align: 'center' },
                  { id: '' },
                  { id: '' },
                ]}
              />
              <TableBody>
                {dataFiltered
                  .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                  .map((row) => (
                    <ClaimsTableRow
                      key={row.claim_header.claim_number}
                      doctors={row.claim_doctors}
                      claim_number={row.claim_header.claim_number}
                      service_date={row.claim_header.Service_Date}                     
                      charged_amnt={row.claim_header.charged_amnt}
                      scheme_paid={row.claim_header.scheme_paid}
                      gap={row.claim_header.gap}
                      plan={account.user.plan}
                      selected={selected.indexOf(row.claim_header.claim_number) !== -1}
                      handleClick={(event) => handleClick(event, row.claim_header.claim_number)}
                      handleViewClick={(event) => handleViewClick(event, row.claim_header.claim_id)}
                      handleTipClick={(event) => handleTipClick(event, row.claim_header.claim_id)}
                    />
                  ))}

                <TableEmptyRows
                  height={77}
                  emptyRows={emptyRows(page, rowsPerPage, claims.length)}
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
