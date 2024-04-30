import PropTypes from 'prop-types';
import { Form,Modal,Input, Divider } from 'antd';
import React, { useRef, useState,useEffect,useContext } from 'react';

import Grid from '@mui/material/Grid';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';

import useAxiosFetch from 'src/hooks/use-axios';

import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

const EditableContext = React.createContext(null);
const EditableRow = ({ index, ...props }) => {
  const [form] = Form.useForm();
  return (
    <Form form={form} component={false}>
      <EditableContext.Provider value={form}>
        <tr {...props} />
      </EditableContext.Provider>
    </Form>
  );
};
const EditableCell = ({
  title,
  editable,
  children,
  dataIndex,
  record,
  handleSave,
  ...restProps
}) => {
  const [editing, setEditing] = useState(false);
  const inputRef = useRef(null);
  const form = useContext(EditableContext);
  useEffect(() => {
    if (editing) {
      inputRef.current.focus();
    }
  }, [editing]);
  const toggleEdit = () => {
    setEditing(!editing);
    form.setFieldsValue({
      [dataIndex]: record[dataIndex],
    });
  };
  const save = async () => {
    try {
      const values = await form.validateFields();
      toggleEdit();
      handleSave({
        ...record,
        ...values,
      });
    } catch (errInfo) {
      console.log('Save failed:', errInfo);
    }
  };
  let childNode = children;
  if (editable) {
    childNode = editing ? (
      <Form.Item
        style={{
          margin: 0,
        }}
        name={dataIndex}
        rules={[
          {
            required: true,
            message: `${title} is required.`,
          },
        ]}
      >
        <Input ref={inputRef} onPressEnter={save} onBlur={save} />
      </Form.Item>
    ) : (
      <div
        className="editable-cell-value-wrap"
        style={{
          paddingRight: 24,
        }}
        onClick={toggleEdit}
        role="button"
        tabIndex={0}
        onKeyDown={(e) => e.key === '1676' && toggleEdit()}
      >
        {children}
      </div>
    );
  }
  return <td {...restProps}>{childNode}</td>;
};

const Scanner = ({lines,document_name,mymodal}) => {
  const [open, setOpen] = useState(false);
  const [postData, setPostData] = useState({});
  const [fetchBtnClicked, setFetchBtnClicked] = useState(false);
  const valueDescription = useRef('');
  const [dataSource, setDataSource] = useState([]);
  /* const [count, setCount] = useState(100); */
  const [documents, setDocument] = useState("");

  const { isLoading, isError, data } = useAxiosFetch('adddocument','POST',postData,fetchBtnClicked);
  useEffect(() => {
    if(fetchBtnClicked)
    {
      setDocument(document_name);
      setPostData(getFormValues());       
      setFetchBtnClicked(false);  
      console.log(data);  
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [fetchBtnClicked]); 
    useEffect(() => {
      setDataSource(...dataSource, lines);
      setDocument(document_name);    
      // eslint-disable-next-line react-hooks/exhaustive-deps
      }, [lines]);   

      useEffect(() => {
        if(mymodal)
        {
          setOpen(true);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
        }, [mymodal]);
      
    const getFormValues = () =>{
      const obj = {
        "category":"---",
        "document":documents,
        "description":valueDescription.current.value,
        "lines":JSON.stringify([])
      };
      console.log(obj);
      return obj;
    }

  const handleOk = (e) => {
    setOpen(true); 
    setFetchBtnClicked(true);
    e.preventDefault();
  };

  const handleCancel = (e) => {
        setOpen(false);
  };

/*
  const handleDelete = (key) => {
    const newData = dataSource.filter((item) => item.key !== key);
    setDataSource(newData);
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
    {
      title: 'operation',
      dataIndex: 'operation',
      render: (_, record) =>
        dataSource.length >= 1 ? (
          <Popconfirm title="Sure to delete?" onConfirm={() => handleDelete(record.key)}>
            <a>Delete</a>
          </Popconfirm>
        ) : null,
    },
  ];
  
  const handleAdd = () => {
    const newData = {
      key: count,
      treatment_date: `-`,
      paid_from: '-',
      amount_charged: `-`,
      amount_paid: `-`,
    };
    setDataSource([...dataSource, newData]);
    setCount(count + 1);
  };

  const handleSave = (row) => {
    const newData = [...dataSource];
    const index = newData.findIndex((item) => row.key === item.key);
    const item = newData[index];
    newData.splice(index, 1, {
      ...item,
      ...row,
    });
    setDataSource(newData);
  };
  
  const components = {
    body: {
      row: EditableRow,
      cell: EditableCell,
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
        handleSave,
      }),
    };
  });
  */
  return (
   
      <Modal
        title="Statement Details (Make sure you save your file)"
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
        okText="Save File"
        cancelText="Close"
      >
      <div>
      <Grid container spacing={2}>
   
          <Grid item xs={12}>
                <TextField
                  autoComplete="given-name"
                  name="query_description"
                  required
                  fullWidth
                  id="query_description"
                  label="Query Description"
                  rows={4}
                  autoFocus
                  multiline
                  inputRef={valueDescription}
                />
                   
              </Grid>
              </Grid>
              <Divider/>
              {/*
      <Button
        onClick={handleAdd}
        type="primary"
        style={{
          marginBottom: 16,
        }}
      >
        Add a row
      </Button>
       
      <Table
        components={components}
        rowClassName={() => 'editable-row'}
        bordered
        dataSource={dataSource}
        columns={columns}
      /> */}
        {isLoading?<Typography>Please wait...</Typography>:null}
      {isError?<Error mymessage={data.message}/>:null}
      {data && !isError?<Success mymessage={data.message}/>:null}
    </div>
       
      </Modal>
   
  );
};
export default Scanner;

EditableCell.propTypes = {
  handleSave: PropTypes.func,
  record: PropTypes.any,
  dataIndex: PropTypes.any,
  children: PropTypes.any,
  title: PropTypes.any,
  editable: PropTypes.any,
};
EditableRow.propTypes = {
  index: PropTypes.any,
};
Scanner.propTypes = {
  lines: PropTypes.any,
  document_name: PropTypes.any,
  mymodal: PropTypes.any,
};