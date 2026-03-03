import React, { useState } from "react";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import Form from "react-bootstrap/Form";
import { useEditStaffMutation } from "../../redux/api/staff/staff-api";
import { toast } from "react-toastify";

function EditStaffModal(props) {
  const [editStaff] = useEditStaffMutation();
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [name, setName] = useState(props.name);
  const [email, setEmail] = useState(props.email);
  const [nameError, setNameError] = useState();
  const [emailError, setEmailError] = useState();

  const handlePostRequest = async () => {
    try {
      const data = { name, id: props?.id };
      await editStaff(data)
        .unwrap()
        .then((res) => {
          handleClose();
          props.refetch();
          toast(res?.message);
        })
        .catch((err) => {
          handleShow();
          toast(err?.data?.message);
        });
    } catch (error) {
      toast(error.message);
    }
  };

  const prepareInput = () => {
    if (typeof name === "undefined") {
      setNameError("Name is required");
    } else {
      setNameError("");
    }

    if (typeof email === "undefined") {
      setEmailError("Email is required");
    } else {
      setEmailError("");
    }

    if (!nameError && !emailError) {
      handlePostRequest();
    }
  };

  return (
    <>
      <Button variant="dark" onClick={handleShow}>
        <i className="fa-solid fa-user-edit"></i>
      </Button>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header className="modal_header_custom" closeButton>
          <Modal.Title>Edit</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form.Group>
            <Form.Label className="custom_label1">Name</Form.Label>
            <Form.Control
              className="custom_input mb-3"
              placeholder="Enter Name"
              aria-label="name"
              aria-describedby="basic-addon1"
              onChange={(e) => setName(e.target.value)}
              value={name}
            />
            <span className="text-danger">{nameError}</span>
          </Form.Group>

          <Form.Group>
            <Form.Label className="custom_label1">Email</Form.Label>
            <Form.Control
              className="custom_input mb-3"
              placeholder="Enter Email"
              aria-label="email"
              aria-describedby="basic-addon1"
              onChange={(e) => setEmail(e.target.value)}
              value={email}
              readOnly
            />
            <span className="text-danger">{emailError}</span>
          </Form.Group>
          <Button variant="primary" className="create_btn" onClick={prepareInput}>
            Save
          </Button>
        </Modal.Body>
      </Modal>
    </>
  );
}

export default EditStaffModal;
