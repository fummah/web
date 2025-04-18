import { Navigate } from "react-router-dom";
import { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Divider from '@mui/material/Divider';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import LoadingButton from '@mui/lab/LoadingButton';
import { alpha, useTheme } from '@mui/material/styles';

import useAxiosFetch from 'src/hooks/use-axios';

import { bgGradient } from 'src/theme/css';

import Logo from 'src/components/logo';
import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function ForgotPasswordView() {
  const theme = useTheme();

  const [postData, setPostData] = useState({});
  const [verifypBtnClicked, setVerifyBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);

    const valueEmail= useRef('');

   const { isLoading: isLoadingVerify, isError: isErrorVerify, data: dataVerify,statusCode: statusCodeVerify } = useAxiosFetch('forgot-password','POST',postData,verifypBtnClicked);
  
    useEffect(() => {
    if(verifypBtnClicked)
    {
      setPostData(getFormValues());  
      setVerifyBtnClicked(false); 
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [verifypBtnClicked]); 

      if(statusCodeVerify === 200)
    {   
      console.log("Dziaba");
      localStorage.removeItem('UNACTIVATEDID');
      localStorage.setItem('RESETEMAIL',valueEmail.current.value);
      return <Navigate to="/reset-password"/>
    }

    const handleForgotPassword = (e) =>{
      setVerifyBtnClicked(true);
      e.preventDefault();
         };


    const getFormValues = () =>{
      const obj = {
        "email":valueEmail.current.value,
              };
      return obj;
    }

  const renderForm = (
    <>
      <Stack spacing={3}>
<Box component="form">

      <Grid container spacing={2}>
              <Grid item xs={12} >
                <TextField
                  autoComplete="given-name"
                  name="email"
                  required
                  fullWidth
                  id="email"
                  label="Email Address"
                  autoFocus
                  inputRef={valueEmail}
                />
              </Grid>
             
              </Grid>
           
              </Box>
       
      </Stack>
  <Divider>-</Divider>
      <LoadingButton
        fullWidth
        size="large"
        type="submit"
        variant="contained"
        color="inherit"
        style={{marginBottom:'10px'}}
        onClick={handleForgotPassword}
        disabled={statusCodeVerify === 200 || isLoadingVerify?disable:enable}
      >
        Send
      </LoadingButton>
      {isLoadingVerify?<Typography>Please wait...</Typography>:null}
      {isErrorVerify?<Error mymessage={dataVerify.message}/>:null}
      {dataVerify && !isErrorVerify?<Success mymessage={dataVerify.message}/>:null}
    </>
  );

  return (
    <Box
      sx={{
        ...bgGradient({
          color: alpha(theme.palette.background.default, 0.9),
          imgUrl: '/assets/background/overlay_4.jpg',
        }),
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
         <Logo
        sx={{
                  top: { xs: 16, md: 24 },
          left: { xs: 16, md: 24 },
          height: 100,
        }}
      />
    

          {renderForm}
        </Card>
      </Stack>
    </Box>
  );
}
