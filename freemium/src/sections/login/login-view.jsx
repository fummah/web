import { Navigate } from "react-router-dom";
import { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Link from '@mui/material/Link';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import TextField from '@mui/material/TextField';
import Typography from '@mui/material/Typography';
import IconButton from '@mui/material/IconButton';
import LoadingButton from '@mui/lab/LoadingButton';
import { alpha, useTheme } from '@mui/material/styles';
import InputAdornment from '@mui/material/InputAdornment';

import { useRouter } from 'src/routes/hooks';

import useAxiosFetch from 'src/hooks/use-axios';

import { bgGradient } from 'src/theme/css';

import Logo from 'src/components/logo';
import Iconify from 'src/components/iconify';
import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function LoginView() {
  const theme = useTheme();

  const router = useRouter();

  const [showPassword, setShowPassword] = useState(false);
    const [postData, setPostData] = useState({});
  const [loginBtnClicked, setPasswordBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);
    const valueEmail= useRef('');
    const valuePassword= useRef('');

   const { isLoading: isLoadingLogin, isError: isErrorLogin, data: dataLogin,statusCode: statusCodeLogin } = useAxiosFetch('login','POST',postData,loginBtnClicked);
  
    useEffect(() => {
    if(loginBtnClicked)
    {
      setPostData(getFormValues());  
      setPasswordBtnClicked(false);     
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [loginBtnClicked]); 

    if(dataLogin)
    {
      if(!isErrorLogin && statusCodeLogin===200)   
      {    
        localStorage.removeItem('UNACTIVATEDID');
        localStorage.setItem('ACCEESS_GRANTED', dataLogin.token);
        localStorage.setItem('USER', JSON.stringify(dataLogin.user));
        
        router.push('/');
         router.reload();
       
      }        
    }
    if(statusCodeLogin)
    {
      if(statusCodeLogin===403)
      {
         localStorage.setItem('UNACTIVATEDID', dataLogin.user.id);
      return <Navigate to="/verify-email"/>
    }
    }
 

  const handleLogin = (e) =>{
 setPasswordBtnClicked(true);
 e.preventDefault();
    };

    const getFormValues = () =>{
      const obj = {
        "email":valueEmail.current.value,
        "password":valuePassword.current.value,
              };
      return obj;
    }

  const renderForm = (   
    <form>
   
      <Stack spacing={3}>
        <TextField name="email" label="Email address" inputRef={valueEmail} />

        <TextField
          name="password"
          label="Password"
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
      </Stack>

      <Stack direction="row" alignItems="center" justifyContent="flex-end" sx={{ my: 3 }}>
        <Link variant="subtitle2" underline="hover">
          Forgot password?
        </Link>
      </Stack>

      <LoadingButton
        fullWidth
        size="large"
        type="submit"
        variant="contained"
        color="inherit"
        style={{marginBottom:'10px'}}
        onClick={handleLogin}
        disabled={statusCodeLogin === 200 || isLoadingLogin?disable:enable}
      >
        Login
      </LoadingButton>
       {isLoadingLogin?<Typography>Please wait...</Typography>:null}
      {isErrorLogin?<Error mymessage={dataLogin.message}/>:null}
      {dataLogin && !isErrorLogin && statusCodeLogin === 200?<Success mymessage={dataLogin.message}/>:null}
    </form>
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
            maxWidth: 420,
          }}
        >
         <Logo
        sx={{
                  top: { xs: 16, md: 24 },
          left: { xs: 16, md: 24 },
        }}
      />
         
          <Typography variant="body2" sx={{ mt: 2, mb: 5 }}>
            Don’t have an account?
            <Link variant="subtitle2" href="/signup" sx={{ ml: 0.5 }}>
              Get started
            </Link>
          </Typography>

          {renderForm}
        </Card>
      </Stack>
    </Box>
  );
}
