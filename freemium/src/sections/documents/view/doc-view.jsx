import { Modal,Table } from 'antd';
import { useState, useEffect } from 'react';

import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Table1 from '@mui/material/Table';
import Button from '@mui/material/Button';
import Container from '@mui/material/Container';
import TableBody from '@mui/material/TableBody';
import Typography from '@mui/material/Typography';
import TableContainer from '@mui/material/TableContainer';
import TablePagination from '@mui/material/TablePagination';

import { useRouter } from 'src/routes/hooks';

import useAxiosFetch from 'src/hooks/use-axios';

import Iconify from 'src/components/iconify';
import Scrollbar from 'src/components/scrollbar';
import Error from 'src/components/response/error';
import Loader from 'src/components/response/loader';

import FormUpload from 'src/sections/overview/upload';

import TableNoData from '../table-no-data';
import UserTableHead from '../user-table-head';
import DocumentTableRow from '../doc-table-row';
import TableEmptyRows from '../table-empty-rows';
import UserTableToolbar from '../user-table-toolbar';
import { emptyRows, applyFilter, getComparator } from '../utils';

// ----------------------------------------------------------------------

export default function DocumentsPage() {

  const router = useRouter();
  const [page, setPage] = useState(0);
  const [order, setOrder] = useState('asc');
  const [selected, setSelected] = useState([]);
  const [indoc, setIndoc] = useState([]);
  const [orderBy, setOrderBy] = useState('name');
  const [filterName, setFilterName] = useState('');
  const [rowsPerPage, setRowsPerPage] = useState(5);
  const [open, setOpen] = useState(false);
  const [open_doc, setOpenDoc] = useState(false);
  const [user,setUser] =  useState({});


  const { isLoading: isLoadingQueries, isError: isErrorQueries, data: dataQueries,statusCode:statusCodeQueries } = useAxiosFetch('getdocuments','GET', {});

  useEffect(() => {
    if(dataQueries && statusCodeQueries===200)
    {
      setIndoc(dataQueries.docs);   
      setUser(dataQueries.user);
      console.log(dataQueries);
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
      const newSelecteds = indoc.map((n) => n.doc_id);
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
/*
  const handleViewClick = (e,lines) =>{
    setDataSource(lines);
    setOpen(true); 
  }
*/
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
    inputData: indoc,
    comparator: getComparator(order, orderBy),
    filterName,
  });

  const handleOk = (e) => {
    setOpen(false); 
   
  };

  const handleCancel = (e) => {
        setOpen(false);
  };
  const defaultColumns = [
    {
      title: 'Treatment Date',
      dataIndex: 'treatment_date',
      width: '30%',
      editable: true,
    },
    {
      title: 'Paid From',
      dataIndex: 'paid_from',
      editable: true,
    },
    
    {
      title: 'Amount Charged',
      dataIndex: 'amount_charged',
      editable: true,
    },
    {
      title: 'Amount Paid',
      dataIndex: 'amount_paid',
      editable: true,
    },   
  ];
  const components = {
    body: {
      
    },
  };
  const columns = defaultColumns.map((col) => {
    if (!col.editable) {
      return col;
    }
    return {
      ...col,
      onCell: (record) => ({
        record,
        editable: col.editable,
        dataIndex: col.dataIndex,
        title: col.title,
      }),
    };
  });

  const notFound = !dataFiltered.length && !!filterName;


  return (
    <Container>
      <Stack direction="row" alignItems="center" justifyContent="space-between" mb={5}>
        <Typography variant="h4">Uploaded Documents</Typography>
        {isLoadingQueries?<Loader/>:null}
        <Button variant="contained" color="inherit" startIcon={<Iconify icon="eva:plus-fill" />} onClick={()=>{setOpenDoc(true)}}>Add Document</Button>
        </Stack>
      {isErrorQueries?<Error mymessage={dataQueries.message}/>:null}
      <Card>
      {open_doc && <div style={{marginLeft:20}}>
      <FormUpload text=''/>
    </div>}
        <UserTableToolbar
          numSelected={selected.length}
          filterName={filterName}
          onFilterName={handleFilterByName}
        />

        <Scrollbar>
          <TableContainer sx={{ overflow: 'unset' }}>
            <Table1 sx={{ minWidth: 800 }}>
              <UserTableHead
                order={order}
                orderBy={orderBy}
                rowCount={indoc.length}
                numSelected={selected.length}
                onRequestSort={handleSort}
                onSelectAllClick={handleSelectAllClick}
                headLabel={[
                  { id: 'documents', label: 'Document Name' },
                  { id: 'description', label: 'Description' },
                  { id: 'date_entered', label: 'Date Entered' },
                  { id: '' },
                ]}
              />
              <TableBody>
                {dataFiltered
                  .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                  .map((row) => (
                    <DocumentTableRow
                      key={row.doc_id}
                      documents={row.actual_documents}
                      description={row.doc_description}                      
                      date_entered={row.date_entered}  
                      doc_id={row.doc_id}  
                      query_id={row.query_id}
                      plan={user?.plan}                 
                      selected={selected.indexOf(row.doc_id) !== -1}
                      handleClick={(e) => handleClick(e, row.doc_id)}
                      handleViewClick={(e) => handleViewClick(e, row.query_id)}
                    />
                  ))}

                <TableEmptyRows
                  height={77}
                  emptyRows={emptyRows(page, rowsPerPage, indoc.length)}
                />

                {notFound && <TableNoData/>}
                {dataFiltered.length<1 && filterName===""?<TableNoData/>:null}
              </TableBody>
            </Table1>
          </TableContainer>
        </Scrollbar>

        <TablePagination
          page={page}
          component="div"
          count={indoc.length}
          rowsPerPage={rowsPerPage}
          onPageChange={handleChangePage}
          rowsPerPageOptions={[5, 10, 25]}
          onRowsPerPageChange={handleChangeRowsPerPage}
        />
      </Card>
      <Modal
        title="Statement Details"
        open={open}
        onOk={handleOk}
        maskClosable={false} 
        onCancel={handleCancel}
        okButtonProps={{
          disabled: false,
        }}
        cancelButtonProps={{
          disabled: false,
          }}
        width="80%"
        okText="Ok"
        cancelText="Close"
      >
 
      <Table
        components={components}
        rowClassName={() => 'editable-row'}
        bordered
        dataSource={{}}
        columns={columns}
      />
       
      </Modal>
    </Container>
  );
}
