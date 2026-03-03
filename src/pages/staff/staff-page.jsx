import { useEffect, useMemo, useState } from "react";
import {
  createColumnHelper,
  flexRender,
  getCoreRowModel,
  useReactTable,
} from "@tanstack/react-table";
import { toast } from "react-toastify";
import Button from "react-bootstrap/Button";
import Table from "react-bootstrap/Table";
import AddStaffModal from "../../Components/staff/add-staff-modal";
import { useChangeStaffStatusMutation, useGetStaffListQuery } from "../../redux/api/staff/staff-api";
import ActionButtonGroup from "../../Components/staff/action-button-group";




export default function StaffPage() {
  const [changeStaffStatus] = useChangeStaffStatusMutation();

  const handleActivateDeactivateAcc = async (id, activate) => {
    try {
      const data = { id, activate };
      await changeStaffStatus(data)
        .unwrap()
        .then((res) => {
          refetch()
          toast(res?.message);
        })
        .catch((err) => {
          toast(err?.data?.message);
        });
    } catch (error) {
      toast(error.message);
    }
  };

  const columnHelper = createColumnHelper();
  const columns = [
    columnHelper.accessor("name", {
      cell: info => info.getValue(),
      header: () => <span>Name</span>,
      footer: info => info.column.id,
    }),
    columnHelper.accessor("email", {
      cell: info => info.getValue(),
      header: () => <span>Email</span>,
      footer: info => info.column.id,
    }),
    columnHelper.accessor("status", {
      cell: info => {
        if (info.getValue() === "active") {
          return (
            <Button
              className="btn btn-success"
              onClick={() => handleActivateDeactivateAcc(info.row.original.id, false)}
            >
              <i className="fa-solid fa-toggle-on"></i>
            </Button>
          );
        }

        if (info.getValue() === "inactive") {
          return (
            <>
              <Button
                className="btn btn-danger"
                onClick={() => handleActivateDeactivateAcc(info.row.original.id, true)}
              >
                <i className="fa-solid fa-toggle-off"></i>
              </Button>
            </>
          );
        }
      },
      header: () => <span>Status</span>,
      footer: info => info.column.id,
    }),
    columnHelper.display({
      id: "actions",
      cell: props => {
        return <ActionButtonGroup
          id={props.row.original.id}
          email={props.row.original.email}
          name={props.row.original.name}
          refetch={refetch}
          permissions={props.row.original.assigned_permissions}
        ></ActionButtonGroup>;
      },
    }),
  ];

  const [{ pageIndex, pageSize }, setPagination] = useState({
    pageIndex: 0,
    pageSize: 10,
  });

  const pagination = useMemo(
    () => ({
      pageIndex,
      pageSize,
    }),
    [pageIndex, pageSize]
  );

  const { data, refetch } = useGetStaffListQuery({ pageIndex, pageSize });

  useEffect(() => {
    refetch();
  }, [pageIndex, pageSize]);

  const table = useReactTable({
    data: data?.data?.rows ?? [],
    columns,
    pageCount: Math.ceil(data?.data?.totalRows / pageSize) ?? 0,
    state: {
      pagination,
    },
    onPaginationChange: setPagination,
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    debugTable: true,
  });

  return (
    <>
      <div className="financial_div sub_Accounts_div">Sub-Accounts  <AddStaffModal refetch={refetch} /></div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <Table className="subaccount_table">
              <thead>
                {table.getHeaderGroups().map(headerGroup => (
                  <tr key={headerGroup.id}>
                    {headerGroup.headers.map(header => (
                      <th key={header.id}>
                        {header.isPlaceholder
                          ? null
                          : flexRender(
                            header.column.columnDef.header,
                            header.getContext()
                          )}
                      </th>
                    ))}
                  </tr>
                ))}
              </thead>
              <tbody>
                {table.getRowModel().rows.map(row => (
                  <tr key={row.id}>
                    {row.getVisibleCells().map(cell => (
                      <td key={cell.id}>
                        {flexRender(cell.column.columnDef.cell, cell.getContext())}
                      </td>
                    ))}
                  </tr>
                ))}
              </tbody>
            </Table>
            <div className="flex items-center gap-2">
              <button
                className="border rounded p-1"
                onClick={() => table.setPageIndex(0)}
                disabled={!table.getCanPreviousPage()}
              >
                {"<<"}
              </button>
              <button
                className="border rounded p-1"
                onClick={() => table.previousPage()}
                disabled={!table.getCanPreviousPage()}
              >
                {"<"}
              </button>
              <button
                className="border rounded p-1"
                onClick={() => table.nextPage()}
                disabled={!table.getCanNextPage()}
              >
                {">"}
              </button>
              <button
                className="border rounded p-1"
                onClick={() => table.setPageIndex(table.getPageCount() - 1)}
                disabled={!table.getCanNextPage()}
              >
                {">>"}
              </button>
              <span className="flex items-center gap-1">
                <div>Page</div>
                <strong>
                  {table.getState().pagination.pageIndex + 1} of{" "}
                  {table.getPageCount()}
                </strong>
              </span>
              <select
                value={table.getState().pagination.pageSize}
                onChange={(e) => {
                  table.setPageSize(Number(e.target.value));
                }}
              >
                {[10, 20, 30, 40, 50].map((pageSize) => (
                  <option key={pageSize} value={pageSize}>
                    Show {pageSize}
                  </option>
                ))}
              </select>
              {/* {dataQuery.isFetching ? "Loading..." : null} */}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}