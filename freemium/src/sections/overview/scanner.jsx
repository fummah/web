import { Modal,Button } from 'antd';
import React, { useState } from 'react';

const Scanner = () => {
  const [open, setOpen] = useState(false);
  const showModal = () => {
    setOpen(true);
  };
  const handleOk = (e) => {
    console.log(e);
    setOpen(false);
  };
  const handleCancel = (e) => {
    console.log(e);
    setOpen(false);
  };
  return (
    <>
      <Button type="primary" onClick={showModal}>
        Scan Now
      </Button>
      <Modal
        title="Statement Details"
        open={open}
        onOk={handleOk}
        onCancel={handleCancel}
        okButtonProps={{
          disabled: true,
        }}
        cancelButtonProps={{
          disabled: true,
        }}
      >
        <p>Empty</p>
       
      </Modal>
    </>
  );
};
export default Scanner;