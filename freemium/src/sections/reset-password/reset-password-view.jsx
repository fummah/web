import { Alert } from 'antd';
import { Navigate } from "react-router-dom";
import { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Link from '@mui/material/Link';
import Grid from '@mui/material/Grid';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Divider from '@mui/material/Divider';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import IconButton from '@mui/material/IconButton';
import LoadingButton from '@mui/lab/LoadingButton';
import { alpha, useTheme } from '@mui/material/styles';
import InputAdornment from '@mui/material/InputAdornment';

import useAxiosFetch from 'src/hooks/use-axios';

import { bgGradient } from 'src/theme/css';

import Logo from 'src/components/logo';
import Iconify from 'src/components/iconify';
import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function ResetPasswordView() {
  const theme = useTheme();

  const [postData, setPostData] = useState({});
  const [showPassword, setShowPassword] = useState(false);
  const [verified, setVerified] = useState(false);
  const [verifypBtnClicked, setVerifyBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);

    const valueTempCode= useRef('');
    const valuePassword= useRef('');
    const valueConfirmPassword= useRef('');

   const { isLoading: isLoadingVerify, isError: isErrorVerify, data: dataVerify,statusCode: statusCodeVerify } = useAxiosFetch('reset-password','POST',postData,verifypBtnClicked);
  
    useEffect(() => {
    if(verifypBtnClicked)
    {
      setPostData(getFormValues());  
      setVerifyBtnClicked(false); 
      setVerified(true);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [verifypBtnClicked]); 



    if(statusCodeVerify === 200)
    {
      localStorage.removeItem('UNACTIVATEDID');
    }
    if(localStorage.getItem("RESETEMAIL") === null)
    {     
        return <Navigate to="/login"/>
    }   

    const handleForgotPassword = (e) =>{
      setVerifyBtnClicked(true);
      e.preventDefault();
         };


    const getFormValues = () =>{
      const obj = {
        "email":localStorage.getItem("RESETEMAIL"),
        "temp_code":valueTempCode.current.value,
        "password":valuePassword.current.value,
        "password_confirmation":valueConfirmPassword.current.value,
              };
      return obj;
    }

  const renderForm = (
    <>
      <Stack spacing={3}>
<Box component="form">
<Typography variant="body2" sx={{ mt: 2, mb: 5 }}>
          <Alert
      message="Password Reset"
      description="A Temporary Code has been sent to your email address."
      type="success"
    />
                 </Typography>
      <Grid container spacing={2}>
              <Grid item xs={12} >
                <TextField
                  autoComplete="given-name"
                  name="temp_code"
                  required
                  fullWidth
                  id="email"
                  label="Temporary Code"
                  autoFocus
                  inputRef={valueTempCode}
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  name="password"
                  label="New Password"
                  id="password"
                  autoComplete="new-password"
                  inputRef={valuePassword}
                   type={showPassword ? 'text' : 'password'}
          InputProps={{
            endAdornment: (
              <InputAdornment position="end">
                <IconButton onClick={() => setShowPassword(!showPassword)} edge="end">
                  <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                </IconButton>
              </InputAdornment>
            ),
          }}
                />
              </Grid>
                <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  name="confirm-password"
                  label="Confirm Password"
                  id="confirm-password"
                  autoComplete="confirm-password"
                  inputRef={valueConfirmPassword}
                   type={showPassword ? 'text' : 'password'}
          InputProps={{
            endAdornment: (
              <InputAdornment position="end">
                <IconButton onClick={() => setShowPassword(!showPassword)} edge="end" >
                  <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                </IconButton>
              </InputAdornment>
            ),
          }}
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
        Reset Password
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
          {verified && <Link variant="subtitle2" href="/login" underline="hover">
          Login Here
        </Link>
}
        </Card>
      </Stack>
    </Box>
  );
}
