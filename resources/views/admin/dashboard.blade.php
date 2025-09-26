@extends('layouts.admin-layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Users</h3>
                <p>1,542</p>
            </div>
            <div class="stat-icon users-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Books</h3>
                <p>8,756</p>
            </div>
            <div class="stat-icon books-icon">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Reviews</h3>
                <p>12,489</p>
            </div>
            <div class="stat-icon reviews-icon">
                <i class="fas fa-star"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Reading Clubs</h3>
                <p>156</p>
            </div>
            <div class="stat-icon clubs-icon">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Recent Users</h2>
            <button class="btn btn-primary">View All</button>
        </div>

        <table class="admin-table">
            <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Join Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>#USR-0258</td>
                <td>Marie Dupont</td>
                <td>marie.dupont@email.com</td>
                <td>24 Sep 2025</td>
                <td><span class="status status-active">Active</span></td>
                <td>
                    <button class="action-btn"><i class="fas fa-eye"></i></button>
                    <button class="action-btn"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
            <tr>
                <td>#USR-0257</td>
                <td>Thomas Martin</td>
                <td>thomas.martin@email.com</td>
                <td>23 Sep 2025</td>
                <td><span class="status status-pending">Pending</span></td>
                <td>
                    <button class="action-btn"><i class="fas fa-eye"></i></button>
                    <button class="action-btn"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Book Management -->
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Add New Book</h2>
            <button class="btn btn-primary">View All Books</button>
        </div>

        <form>
            <div class="form-group">
                <label class="form-label">Book Title</label>
                <input type="text" class="form-control" placeholder="Enter book title">
            </div>

            <div class="form-group">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" placeholder="Enter author name">
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control" placeholder="Enter book description"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Genre</label>
                <select class="form-control">
                    <option value="">Select genre</option>
                    <option value="fiction">Fiction</option>
                    <option value="non-fiction">Non-Fiction</option>
                    <option value="science-fiction">Science Fiction</option>
                    <option value="fantasy">Fantasy</option>
                    <option value="mystery">Mystery</option>
                    <option value="romance">Romance</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Book Cover</label>
                <input type="file" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
@endsection
