import { Modal } from 'antd';
import { Navigate } from "react-router-dom";
import { useRef, useState, useEffect } from 'react';

import Box from '@mui/material/Box';
import Grid from '@mui/material/Grid';
import Link from '@mui/material/Link';
import Card from '@mui/material/Card';
import Stack from '@mui/material/Stack';
import Select from '@mui/material/Select';
import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';
import Checkbox from '@mui/material/Checkbox';
import MenuItem from '@mui/material/MenuItem';
import TextField from '@mui/material/TextField';
import InputLabel from '@mui/material/InputLabel';
import Typography from '@mui/material/Typography';
import IconButton from '@mui/material/IconButton';
import LoadingButton from '@mui/lab/LoadingButton';
import FormControl from '@mui/material/FormControl';
import { alpha, useTheme } from '@mui/material/styles';
import InputAdornment from '@mui/material/InputAdornment';
import FormControlLabel from '@mui/material/FormControlLabel';

import useAxiosFetch from 'src/hooks/use-axios';

import { bgGradient } from 'src/theme/css';

import Logo from 'src/components/logo';
import Iconify from 'src/components/iconify';
import Error from 'src/components/response/error';
import Success from 'src/components/response/success';

// ----------------------------------------------------------------------

export default function SignUpView() {
  const theme = useTheme();

  const [showPassword, setShowPassword] = useState(false);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [postData, setPostData] = useState({});
  const [signupBtnClicked, setSignupBtnClicked] = useState(false);
  const [enable] = useState(false);
  const [disable] = useState(true);
  const [termschecked, setTermsChecked] = useState(false);
    const [medical_scheme, setMedicaScheme] = useState('');
    const [schemes, setSchemes] = useState([]);
    const valueFirstName= useRef('');
    const valueLastName= useRef('');
    const valueEmail= useRef('');
    const valueIdNumber= useRef('');
    const valueSchemeNumber= useRef('');
    const valuePassword= useRef('');
    const valueConfirmPassword= useRef('');

   const handleChange = (event) => {
    setMedicaScheme(event.target.value);
  };

  const { isError: isErrorScheme, data: dataScheme } = useAxiosFetch('getschemes'); 

   const { isLoading: isLoadingAddButton, isError: isErrorAddButton, data: dataAddButton,statusCode: statusCodeSignup } = useAxiosFetch('adduser','POST',postData,signupBtnClicked);

   useEffect(() => {
    if(dataScheme && statusCodeSignup===200)
    {
      setSchemes(dataScheme.schemes);       
    }   
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [dataScheme]);   

    useEffect(() => {
    if(signupBtnClicked)
    {
      setPostData(getFormValues());       
      setSignupBtnClicked(false);       
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [signupBtnClicked]);   

  const handleSignUp = (e) =>{
 setSignupBtnClicked(true);
 e.preventDefault();
    };

    const getFormValues = () =>{
      const obj = {
        "first_name":valueFirstName.current.value,
        "last_name":valueLastName.current.value,
        "email":valueEmail.current.value,
        "id_number":valueIdNumber.current.value,
        "scheme_number":valueSchemeNumber.current.value,
        "password":valuePassword.current.value,
        "password_confirmation":valueConfirmPassword.current.value,
        "scheme_name":medical_scheme
      };
      return obj;
    }

    if(dataAddButton && !isErrorAddButton && statusCodeSignup === 200)
    {
      localStorage.setItem('UNACTIVATEDID', dataAddButton.user.id);
      return <Navigate to="/verify-email"/>
    }
    const handleOk = () => {
      setIsModalOpen(false);
    };
    const handleCancel = () => {
      setIsModalOpen(false);
    };
    const handleTCClick = () =>{
      setIsModalOpen(true);
    }
    const handleTerms = (event) => {
      setTermsChecked(event.target.checked);
    };

  const renderForm = (
    <>
      <Stack spacing={3}>
<Box component="form">

      <Grid container spacing={2}>
              <Grid item xs={12} sm={6}>
                <TextField
                  autoComplete="given-name"
                  name="firstName"
                  required
                  fullWidth
                  id="firstName"
                  label="First Name"
                  autoFocus
                  inputRef={valueFirstName}
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="lastName"
                  label="Last Name"
                  name="lastName"
                  autoComplete="family-name"
                  inputRef={valueLastName}
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="email"
                  label="Email Address"
                  name="email"
                  autoComplete="email"
                  inputRef={valueEmail}
                />
              </Grid>
               <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="id-number"
                  label="ID Number"
                  name="id-number"
                  autoComplete="id-number"
                  inputRef={valueIdNumber}
                />
              </Grid>
                 <Grid item xs={12} sm={6}>
                 <FormControl sx={{ m: 1, minWidth: 240 }}>
                  <InputLabel id="demo-select-small-label">Medical Scheme</InputLabel>
                   <Select
        labelId="demo-select-small-label"
        id="medical-scheme"
        value={medical_scheme}
        label="Medical Scheme"
        onChange={handleChange}
      >
         <MenuItem value="">
          <em>None</em>
        </MenuItem>
{!isErrorScheme && dataScheme ? schemes.map((scheme,index)=>(
        <MenuItem value={scheme} key={index}>{scheme}</MenuItem>
       )):null}
      </Select>
          </FormControl>
              </Grid>
               <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  id="scheme-number"
                  label="Scheme Number"
                  name="scheme-number"
                  autoComplete="scheme-number"
                  inputRef={valueSchemeNumber}
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  required
                  fullWidth
                  name="password"
                  label="Password"
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
                <IconButton onClick={() => setShowPassword(!showPassword)} edge="end">
                  <Iconify icon={showPassword ? 'eva:eye-fill' : 'eva:eye-off-fill'} />
                </IconButton>
              </InputAdornment>
            ),
          }}
                />
              </Grid>
              <Grid item xs={12}>
                <FormControlLabel
                  control={<Checkbox value="allowExtraEmails" color="primary" checked={termschecked} onChange={handleTerms}/>}
                  label="I accept"
                />
                  <FormControlLabel
                  control={<Button value="allowExtraEmails" color="primary" onClick={handleTCClick}>Terms & Conditions</Button>}
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
        onClick={handleSignUp}
        disabled={statusCodeSignup === 200 || isLoadingAddButton || !termschecked?disable:enable}
      >
        Sign Up
      </LoadingButton>
      {isLoadingAddButton?<Typography>Please wait...</Typography>:null}
      {isErrorAddButton?<Error mymessage={dataAddButton.message}/>:null}
      {dataAddButton && !isErrorAddButton && statusCodeSignup === 200?<Success mymessage={dataAddButton.message}/>:null}
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
         
          <Typography variant="body2" sx={{ mt: 2, mb: 5 }}>
            Already have an account?
            <Link variant="subtitle2" href="/login" sx={{ ml: 0.5 }}>
              Sign in
            </Link>
          </Typography>

          {renderForm}
        </Card>
      </Stack>
      <Modal title="Terms & Conditions" open={isModalOpen} onOk={handleOk} onCancel={handleCancel}>
        <p>Content Here</p>        
      </Modal>
    </Box>
  );
}
