import { Upload, message } from 'antd';
import { InboxOutlined } from '@ant-design/icons';
import React, { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Card from '@mui/material/Card';
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

const { Dragger } = Upload;

// ----------------------------------------------------------------------

const props = {
  name: 'file',
  multiple: true,
  action: 'https://run.mocky.io/v3/435e224c-44fb-4773-9faf-380c5e6a2188',
  onChange(info) {
    const { status } = info.file;
    if (status !== 'uploading') {
      console.log(info.file, info.fileList);
    }
    if (status === 'done') {
      message.success(`${info.file.name} file uploaded successfully.`);
    } else if (status === 'error') {
      message.error(`${info.file.name} file upload failed.`);
    }
  },
  onDrop(e) {
    console.log('Dropped files', e.dataTransfer.files);
  },
};

const AddQueryForm = React.memo(() => {
  const [category, setCategory] = useState('');
  const [postData, setPostData] = useState({});
  const [fetchBtnClicked, setFetchBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);
  const valueDescription = useRef('');

   const { isLoading, isError, data,statusCode } = useAxiosFetch('addquery','POST',postData,fetchBtnClicked);

   useEffect(() => {
    if(fetchBtnClicked)
    {
      setPostData(getFormValues());   
      setFetchBtnClicked(false);    
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [fetchBtnClicked]);   
 
    if(data)
    {
      console.log(data);
    }
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
        "category":category,
        "description":valueDescription.current.value
      };
      return obj;
    }

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
                        <Grid item xs={12}>              

      <Dragger {...props}>
    <p className="ant-upload-drag-icon">
      <InboxOutlined />
    </p>
    <p className="ant-upload-text">Click or drag file to this area to upload</p>   
  </Dragger>

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
