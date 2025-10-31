import React from "react";
import FileManager from "@/components/layout/FileManagerFixed";

export default function DashboardPage() {
  return (
    <div>
      <h1 className="text-2xl font-semibold mb-4">Welcome to the Dashboard</h1>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="p-4 bg-white dark:bg-slate-800 rounded shadow card-appear">Card 1</div>
        <div className="p-4 bg-white dark:bg-slate-800 rounded shadow card-appear">Card 2</div>
        <div className="p-4 bg-white dark:bg-slate-800 rounded shadow card-appear">Card 3</div>
      </div>

      <div className="mt-8">
        <FileManager />
      </div>
    </div>
  );
}
