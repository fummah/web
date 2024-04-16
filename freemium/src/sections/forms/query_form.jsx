import PropTypes from 'prop-types';
import { Form,Card,Upload, message } from 'antd';
import { InboxOutlined } from '@ant-design/icons';
import React, { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Stack from '@mui/material/Stack';
import Select from '@mui/material/Select';
import Divider from '@mui/material/Divider';
import MenuItem from '@mui/material/MenuItem';
import TextField from '@mui/material/TextField';
import InputLabel from '@mui/material/InputLabel';
import Typography from '@mui/material/Typography';
import LoadingButton from '@mui/lab/LoadingButton';
import FormControl from '@mui/material/FormControl';

import useAxiosFetch from 'src/hooks/use-axios';

import { account } from 'src/_mock/account';

import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

const formItemLayout = {
  labelCol: {
    span: 6,
  },
  wrapperCol: {
    span: 14,
  },
};

const beforeUpload = (file) => {
  const isPDF = file.type === 'application/pdf';
  if (!isPDF) {
    message.error('You can only upload PDF files!');
  }
  return isPDF;
};
const onFinish = (values) => {
  console.log('Received values of form: ', values);
};


const AddQueryForm = React.memo(({claim_id}) => {
  const [category, setCategory] = useState('');
  const [postData, setPostData] = useState({});
  const [fetchBtnClicked, setFetchBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);
  const valueDescription = useRef('');
  const [document_name,setDocumentName] = useState("");
  

   const { isLoading, isError, data,statusCode } = useAxiosFetch('addquery','POST',postData,fetchBtnClicked);

   useEffect(() => {
    if(fetchBtnClicked)
    {
      setPostData(getFormValues());   
      setFetchBtnClicked(false);  
      }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [fetchBtnClicked]);   
 
  
  const handleChange = (event) => {
    setCategory(event.target.value);
     
  };

  const HandleAddQuery = (e) =>{
 setFetchBtnClicked(true);
 e.preventDefault();
    };

    const getFormValues = () =>{
      const obj = {
        "id":account.user.id,
        "claim_id":claim_id,
        "document":document_name,
        "category":category,
        "description":valueDescription.current.value,
        "lines":JSON.stringify([])
      };
      return obj;
    }
    const normFile = (e) => {
    
      if (Array.isArray(e)) {
        return e;
      }
      if(e.fileList[0].status==="done")
      {
            setDocumentName(e.file.name);
      }  
      return e?.fileList;
    };

  

  const renderForm = ( 
      <Stack spacing={3}>
<Box component="form">
<Typography variant="h5">Add Query</Typography>
 <Divider>-</Divider>
      <Grid container spacing={2}>
              <Grid item xs={12}>
                <FormControl sx={{ m: 1, minWidth: '100%' }}>
                  <InputLabel id="demo-select-small-label">Categories</InputLabel>
                   <Select
        labelId="demo-select-small-label"
        id="category"
        value={category}
        label="Category"
        onChange={handleChange}
      >
         <MenuItem value="">
          <em>None</em> 
        </MenuItem>
        <MenuItem value="Chronic">Chronic</MenuItem>
        <MenuItem value="Benefit Help">Benefit Help</MenuItem>
        <MenuItem value="Others">Others</MenuItem>
      </Select>
          </FormControl>
                  
              </Grid>
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
                                    <Grid item xs={12} >              
                        <Form
    name="validate_other"
    {...formItemLayout}
    onFinish={onFinish}
    initialValues={{
      'input-number': 3,
      'checkbox-group': ['A', 'B'],
      rate: 3.5,
      'color-picker': null,
    }}
    style={{
      maxWidth: '100%',
      marginTop:20,
    }}    
  >
                        <Form.Item>
      <Form.Item name="dragger" valuePropName="fileList" getValueFromEvent={normFile} noStyle>
        <Upload.Dragger accept=".pdf" name="file" action="https://medclaimassist.co.za/testing/freemium_upload.php" beforeUpload={beforeUpload}>
          <p className="ant-upload-drag-icon">
            <InboxOutlined />
          </p>          
          <p className="ant-upload-hint">Click or drag file to this area to upload</p>
        </Upload.Dragger>
      </Form.Item>
    </Form.Item>
  </Form>

              </Grid>
              <Grid item xs={12}>
  <Divider>-</Divider>
      <LoadingButton
        fullWidth
        size="large"
        type="submit"
        variant="contained"
        color="inherit"
        onClick={HandleAddQuery}
        style={{marginBottom:'10px'}}
        disabled={statusCode === 200 || isLoading?disable:enable}
      >
        Add Query
      </LoadingButton>

      {isLoading?<Typography>Please wait...</Typography>:null}
      {isError?<Error mymessage={data.message}/>:null}
      {data && !isError?<Success mymessage={data.message}/>:null}
              </Grid>
            
              </Grid>
              </Box>
       
      </Stack>
    
  );

  return (
    <Box
      sx={{
        height: 1,
      }}
    >
     
      <Stack alignItems="center" justifyContent="center" sx={{ height: 1 }}>
        <Card
          sx={{
            p: 5,
            width: 1,
            maxWidth: 600,
          }}
        >
        
          {renderForm}
        </Card>
      </Stack>
    </Box>
  );
});

export default AddQueryForm;
AddQueryForm.propTypes = {
  claim_id: PropTypes.any,
};
